<?php

namespace App\Http\Controllers;

use App\Services\TeamService;
use App\Http\Requests\TeamProcessRequest;

class TeamController extends Controller
{
    private $teamService;

    public function __construct(TeamService $teamService) {
        $this->teamService = $teamService;
    }

    public function process(TeamProcessRequest $request) {
        $team = $this->teamService->selectBestTeam($request->validated());
        return response()->json($team);
    }
}
