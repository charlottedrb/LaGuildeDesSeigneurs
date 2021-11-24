<?php

namespace App\Services;

use LogicException;
use App\Entity\Player;
use App\Form\PlayerType;
use App\Event\PlayerEvent;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PlayerService implements PlayerServiceInterface
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private EntityManagerInterface $em,
        private FormFactoryInterface $formFactory,
        private ValidatorInterface $validator,
        private EventDispatcherInterface $dispatcher
    ) { }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $playersFinal = [];
        $players = $this->playerRepository->findAll();

        foreach ($players as $player) {
            $playersFinal[] = $player->toArray();
        }

        return $playersFinal;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $data)
    {
        $player = new Player();

        $player
            ->setModification(new \DateTime())
            ->setIdentifier(hash('sha1', uniqid()))
            ->setCreation(new \DateTime())
        ;

        $this->submit($player, PlayerType::class, $data);
        $this->isEntityFilled($player);

        $this->em->persist($player);
        $this->em->flush();

        return $player;
    }

    /**
     * {@inheritdoc}
     */
    public function isEntityFilled(Player $player)
    {
        $errors = $this->validator->validate($player);
        if(count($errors) > 0) {
            throw new UnprocessableEntityHttpException((string) $errors . ' Missing data for Entity -> ' . $this->serializeJson($player));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submit(Player $player, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);

        //Bad array
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
        }

        //Submits form
        $form = $this->formFactory->create($formName, $player, ['csrf_protection' => false]);
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
    public function modify(Player $player, string $data)
    {
        $player
            ->setModification(new \DateTime())
        ;

        $this->submit($player, PlayerType::class, $data);
        $this->isEntityFilled($player);

        // Dispatch event 
        $event = new PlayerEvent($player);
        $this->dispatcher->dispatch($event, PlayerEvent::PLAYER_MODIFIED);

        $this->em->persist($player);
        $this->em->flush();

        return $player;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Player $player)
    {
        $this->em->remove($player);

        return $this->em->flush();
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
}
