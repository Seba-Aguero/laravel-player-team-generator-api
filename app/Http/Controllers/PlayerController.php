<?php

namespace App\Http\Controllers;

use App\Services\PlayerService;
use App\Http\Requests\PlayerStoreRequest;
use App\Http\Resources\PlayerCollectionResource;
use App\Http\Resources\PlayerResource;
use Illuminate\Http\JsonResponse;

class PlayerController extends Controller
{
    private $playerService;

    public function __construct(PlayerService $playerService) {
        $this->playerService = $playerService;
    }

    public function index(): PlayerCollectionResource {
        $players = $this->playerService->getAllPlayers();
        return new PlayerCollectionResource($players);
    }

    public function show($id): PlayerResource {
        return new PlayerResource($this->playerService->getPlayerById($id));
    }

    public function store(PlayerStoreRequest $request): PlayerResource {
        $player = $this->playerService->createPlayer($request->validated());
        return new PlayerResource($player);
    }

    public function update(PlayerStoreRequest $request, $id): PlayerResource {
        $player = $this->playerService->updatePlayer($id, $request->validated());
        return new PlayerResource($player);
    }

    public function destroy($id): JsonResponse {
        $this->playerService->deletePlayer($id);
        return response()->json(null, 204);
    }
}
