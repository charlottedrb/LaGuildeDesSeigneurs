<?php

namespace App\Controller;

use App\Entity\Player;
use App\Services\PlayerServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class PlayerController extends AbstractController
{
    private $playerService;

    public function __construct(PlayerServiceInterface $playerService)
    {
        $this->playerService = $playerService;
    }

    #[Route('/player', name: 'player_redirect_index', methods: ['HEAD', 'GET'])]
    public function redirectIndex()
    {
        return $this->redirectToRoute('player_index');
    }

    #[Route('/player/index', name: 'player_index', methods: ['HEAD', 'GET'])]
    public function index(): JsonResponse
    {
        $this->denyAccessUnlessGranted('playerIndex', null);

        $players = $this->playerService->getAll();

        return JsonResponse::fromJsonString($this->playerService->serializeJson($players));
    }

    #[Route('/player/display/{identifier}', name: 'player_display', requirements: ['identifier' => '^([a-z0-9]{40})$'], methods: ['HEAD', 'GET'])]
    #[Entity('player', expr:'repository.findOneByIdentifier(identifier)')]
    /**
     * @OA\Parameter(name= "identifier", in= "path", description= "identifier for the Player", required= true)
     * @OA\Response(response= 200, description= "Success", @Model(type= Player::class))
     * @OA\Response(response= 403, description= "Access denied")
     * @OA\Response(response= 404, description= "Not found")
     * @OA\Tag(name= "Player")
     */
    public function display(Player $player)
    {
        $this->denyAccessUnlessGranted('playerDisplay', $player);
        return JsonResponse::fromJsonString($this->playerService->serializeJson($player));
    }

    #[Route('/player/create', name: 'player_create', methods: ['HEAD', 'POST'])]
     /**
     * @OA\Parameter(name= "identifier", in= "path", description= "identifier for the Player", required= true)
     * @OA\Response(response= 200, description= "Success", @Model(type= Player::class))
     * @OA\Response(response= 403, description= "Access denied")
     * @OA\Response(response= 404, description= "Not found")
     * @OA\Tag(name= "Player")
     */
    public function create(Request $request)
    {
        $this->denyAccessUnlessGranted('playerCreate', null);
        $player = $this->playerService->create($request->getContent());

        return JsonResponse::fromJsonString($this->playerService->serializeJson($player));
    }

    #[Route('/player/modify/{identifier}', name: 'player_modify', requirements: ['identifier' => '^([a-z0-9]{40})$'], methods: ['PUT', 'HEAD'])]
     /**
     * @OA\Parameter(name= "identifier", in= "path", description= "identifier for the Player", required= true)
     * @OA\Response(response= 200, description= "Success", @Model(type= Player::class))
     * @OA\Response(response= 403, description= "Access denied")
     * @OA\Response(response= 404, description= "Not found")
     * @OA\Tag(name= "Player")
     */
    public function modify(Player $player, Request $request)
    {
        $player = $this->playerService->modify($player, $request->getContent());
        $this->denyAccessUnlessGranted('playerModify', $player);

        return JsonResponse::fromJsonString($this->playerService->serializeJson($player));
    }

    #[Route('/player/delete/{identifier}', name: 'player_delete', requirements: ['identifier' => '^([a-z0-9]{40})$'], methods: ['DELETE', 'HEAD'])]
     /**
     * @OA\Parameter(name= "identifier", in= "path", description= "identifier for the Player", required= true)
     * @OA\Response(response= 200, description= "Success", @Model(type= Player::class))
     * @OA\Response(response= 403, description= "Access denied")
     * @OA\Response(response= 404, description= "Not found")
     * @OA\Tag(name= "Player")
     */
    public function delete(Player $player)
    {
        $player = $this->playerService->delete($player);
        $this->denyAccessUnlessGranted('playerDelete', $player);
    }
}
