<?php

namespace App\Controller;

use App\Entity\Character;
use App\Services\CharacterServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{
    private $characterService;

    public function __construct(CharacterServiceInterface $characterService)
    {
        $this->characterService = $characterService;
    }

    #[Route('/character', name: 'character_redirect_index', methods: ['HEAD', 'GET'])]
    public function redirectIndex() 
    {
        return $this->redirectToRoute('character_index');
    }

    #[Route('/character/index', name: 'character_index', methods: ['HEAD', 'GET'])]
    public function index(): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterIndex', null);

        $characters = $this->characterService->getAll();

        return new JsonResponse($characters);
    }

    #[Route('/character/display/{identifier}', name: 'character_display', requirements: ['identifier' => '^([a-z0-9]{40})$'], methods: ['HEAD', 'GET'])]
    public function display(Character $character)
    {
        $this->denyAccessUnlessGranted('characterDisplay', $character);
        return new JsonResponse($character->toArray());
    }

    #[Route('/character/create', name: 'character_create', methods: ['HEAD', 'POST'])]
    public function create()
    {
        $this->denyAccessUnlessGranted('characterCreate', null);
        $character = $this->characterService->create();

        return new JsonResponse($character->toArray());
    }

    #[Route('/character/modify/{identifier}', name: 'character_modify', requirements: ['identifier' => '^([a-z0-9]{40})$'], methods: ['PUT', 'HEAD'])]
    public function modify(Character $character)
    {
        $character = $this->characterService->modify($character);
        $this->denyAccessUnlessGranted('characterModify', $character);

        return new JsonResponse($character->toArray());
    }

    #[Route('/character/delete/{identifier}', name: 'character_delete', requirements: ['identifier' => '^([a-z0-9]{40})$'], methods: ['DELETE', 'HEAD'])]
    public function delete(Character $character)
    {
        $character = $this->characterService->delete($character);
        $this->denyAccessUnlessGranted('characterDelete', $character);
    }

    #[Route('/character/images/{number}', name: 'character_images', requirements: ['number' => '^([0-9]{1,2})$'], methods: ['GET', 'HEAD'])]
    public function images(int $number)
    {
        $this->denyAccessUnlessGranted('characterIndex', null); 
        $images = $this->characterService->getImages($number);

        return new JsonResponse($images);
    }

    #[Route('/character/images/{kind}/{number}', name: 'character_images_by_kind', requirements: ['kind' => '^(dames|ennemies|ennemis|seigneurs)$', 'number' => '^([0-9]{1,2})$'], methods: ['GET', 'HEAD'])]
    public function imagesByKind(string $kind, int $number)
    {
        $this->denyAccessUnlessGranted('characterIndex', null); 

        return new JsonResponse($this->characterService->getImagesByKind($kind, $number));
    }
}
