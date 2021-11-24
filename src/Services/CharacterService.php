<?php

namespace App\Services;

use DateTime;
use LogicException;
use App\Entity\Character;
use App\Form\CharacterType;
use App\Event\CharacterEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Finder\Finder;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CharacterService implements CharacterServiceInterface
{
    public function __construct(
        private CharacterRepository $characterRepository,
        private EntityManagerInterface $em,
        private FormFactoryInterface $formFactory,
        private ValidatorInterface $validator,
        private EventDispatcherInterface $dispatcher
    ) {}

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        return $this->characterRepository->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $data)
    {
        //Use with {"kind":"Dame","name":"Eldalótë","surname":"Fleur elfique","caste":"Elfe","knowledge":"Arts","intelligence":120,"life":12,"image":"/images/eldalote.jpg"}
        $character = new Character();
        $this->submit($character, CharacterType::class, $data);
        
        return $this->createFromHtml($character);
    }

    /**
     * {@inheritdoc}
     */
    public function isEntityFilled(Character $character)
    {
        $errors = $this->validator->validate($character);

        if ($errors->count() > 0) {
            throw new UnprocessableEntityHttpException((string) $errors . ' Missing data for Entity -> ' . $this->serializeJson($character));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submit(Character $character, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);

        //Bad array
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
        }

        //Submits form
        $form = $this->formFactory->create($formName, $character, ['csrf_protection' => false]);
        $form->submit($dataArray, false);//With false, only submitted fields are validated

        //Gets errors
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            throw new LogicException('Error ' . get_class($error->getCause()) . ' --> ' . $error->getMessageTemplate() . ' ' . json_encode($error->getMessageParameters()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function modify(Character $character, string $data)
    {
        $this->submit($character, CharacterType::class, $data);
        
        return $this->modifyFromHtml($character);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Character $character)
    {
        $this->em->remove($character);

        return $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getImages(int $number, ?string $kind = null)
    {
        $folder = __DIR__ . '/../../public/images';

        $finder = new Finder();
        $finder
            ->files()
            ->in($folder)
            ->notPath('/cartes/')
            ->sortByName()
        ;

        if (null !== $kind) {
            $finder->path('/' . $kind .'/');
        }

        $images = [];
        foreach ($finder as $file) {
            $images[] = '/images/' . $file->getRelativePathname();
        }
        shuffle($images);

        return array_slice($images, 0, $number, true);
    }

    /**
     * {@inheritdoc}
     */
    public function serializeJson($data)
    {
        $encoders = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($data) {
                return $data->getIdentifier();
            }
        ];
        $normalizers = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizers], [$encoders]);

        return $serializer->serialize($data, 'json');
    }

    /**
     * Create a Character from HTML
     *
     * @param Character $character
     * @return Character
     */
    public function createFromHtml(Character $character)
    {
        $character
            ->setIdentifier(hash('sha1', uniqid()))
            ->setCreation(new DateTime())
            ->setModification(new DateTime())
        ;
        $this->isEntityFilled($character);

        // Dispatch event 
        $event = new CharacterEvent($character);
        $this->dispatcher->dispatch($event, CharacterEvent::CHARACTER_CREATED);
        $this->dispatcher->dispatch($event, CharacterEvent::CHARACTER_LIFE_20);

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }

    /**
     * Modify a Character
     *
     * @param Character $character
     * @return Character
     */
    public function modifyFromHtml(Character $character)
    {
        $this->isEntityFilled($character);

        $character->setModification(new \DateTime());

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }
}
