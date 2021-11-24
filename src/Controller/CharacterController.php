<?php

namespace App\Controller;

use App\Entity\Character;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use App\Services\CharacterServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class CharacterController extends AbstractController
{
    public function __construct(private CharacterServiceInterface $characterService)
    { }

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

        return JsonResponse::fromJsonString($this->characterService->serializeJson($characters));
    }

    #[Route('/character/display/{identifier}', name: 'character_display', requirements: ['identifier' => '^([a-z0-9]{40})$'], methods: ['HEAD', 'GET'])]
    #[Entity('character', expr: 'repository.findOneByIdentifier(identifier)')]
    /**
     * @OA\Parameter(name= "identifier", in= "path", description= "identifier for the Character", required= true)
     * @OA\Response(response= 200, description= "Success", @Model(type= Character::class))
     * @OA\Response(response= 403, description= "Access denied")
     * @OA\Response(response= 404, description= "Not found")
     * @OA\Tag(name= "Character")
     */
    public function display(Character $character)
    {
        $this->denyAccessUnlessGranted('characterDisplay', $character);
        return JsonResponse::fromJsonString($this->characterService->serializeJson($character));
    }

    #[Route('/character/create', name: 'character_create', methods: ['HEAD', 'POST'])]
    /**
     * @OA\Parameter(name= "identifier", in= "path", description= "identifier for the Character", required= true)
     * @OA\Response(response= 200, description= "Success", @Model(type= Character::class))
     * @OA\Response(response= 403, description= "Access denied")
     * @OA\Response(response= 404, description= "Not found")
     * @OA\Tag(name= "Character")
     */
    public function create(Request $request)
    {
        $this->denyAccessUnlessGranted('characterCreate', null);
        $character = $this->characterService->create($request->getContent());

        return JsonResponse::fromJsonString($this->characterService->serializeJson($character));
    }

    #[Route('/character/modify/{identifier}', name: 'character_modify', requirements: ['identifier' => '^([a-z0-9]{40})$'], methods: ['PUT', 'HEAD'])]
    /**
     * @OA\Parameter(name= "identifier", in= "path", description= "identifier for the Character", required= true)
     * @OA\Response(response= 200, description= "Success", @Model(type= Character::class))
     * @OA\Response(response= 403, description= "Access denied")
     * @OA\Response(response= 404, description= "Not found")
     * @OA\Tag(name= "Character")
     */
    public function modify(Character $character, Request $request)
    {
        $character = $this->characterService->modify($character, $request->getContent());
        $this->denyAccessUnlessGranted('characterModify', $character);

        return JsonResponse::fromJsonString($this->characterService->serializeJson($character));
    }

    #[Route('/character/delete/{identifier}', name: 'character_delete', requirements: ['identifier' => '^([a-z0-9]{40})$'], methods: ['DELETE', 'HEAD'])]
    /**
     * @OA\Parameter(name= "identifier", in= "path", description= "identifier for the Character", required= true)
     * @OA\Response(response= 200, description= "Success", @Model(type= Character::class))
     * @OA\Response(response= 403, description= "Access denied")
     * @OA\Response(response= 404, description= "Not found")
     * @OA\Tag(name= "Character")
     */
    public function delete(Character $character)
    {
        $character = $this->characterService->delete($character);
        $this->denyAccessUnlessGranted('characterDelete', $character);
    }

    #[Route('/character/images/{number}', name: 'character_images', requirements: ['number' => '^([0-9]{1,2})$'], methods: ['GET', 'HEAD'])]
    /**
     * @OA\Parameter(name= "number", in= "path", description= "number of images to generate", required= true)
     * @OA\Response(response= 200, description= "Success", @Model(type= Character::class))
     * @OA\Response(response= 403, description= "Access denied")
     * @OA\Response(response= 404, description= "Not found")
     * @OA\Tag(name= "Character")
     */
    public function images(int $number)
    {
        $this->denyAccessUnlessGranted('characterIndex', null);
        $images = $this->characterService->getImages($number);

        return new JsonResponse($images);
    }

    #[Route('/character/images/{kind}/{number}', name: 'character_images_by_kind', requirements: ['kind' => '^(dames|ennemies|ennemis|seigneurs)$', 'number' => '^([0-9]{1,2})$'], methods: ['GET', 'HEAD'])]
    /**
     * @OA\Parameter(name= "kind", in= "path", description= "kind for the Character", required= true)
     * @OA\Parameter(name= "number", in= "path", description= "number of images to generate", required= true)
     * @OA\Response(response= 200, description= "Success", @Model(type= Character::class))
     * @OA\Response(response= 403, description= "Access denied")
     * @OA\Response(response= 404, description= "Not found")
     * @OA\Tag(name= "Character")
     */
    public function imagesByKind(string $kind, int $number)
    {
        $this->denyAccessUnlessGranted('characterIndex', null);

        return new JsonResponse($this->characterService->getImages($number, $kind));
    }

    #[Route('/character/intelligence/{level}', name: 'character_by_intelligence', requirements: ['level' => '^([0-9]{1,3})$'], methods: ['GET', 'HEAD'])]
    /**
     * @OA\Parameter(name= "level", in= "path", description= "kind for the Character", required= true)
     * @OA\Response(response= 200, description= "Success", @Model(type= Character::class))
     * @OA\Response(response= 403, description= "Access denied")
     * @OA\Response(response= 404, description= "Not found")
     * @OA\Tag(name= "Character")
     */
    public function getAllByIntelligenceLevel(int $level) 
    {
        $this->denyAccessUnlessGranted('characterIndex', null);

        $characters = $this->characterService->getAllByIntelligenceLevel($level);

        return JsonResponse::fromJsonString($this->characterService->serializeJson($characters));
    }
}
