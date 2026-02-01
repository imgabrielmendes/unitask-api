<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\TeamService;
use App\Services\TaskService;

class HomeController extends Controller
{
    protected TaskService $taskService;
    protected TeamService $teamService;

    public function __construct(
        TaskService $taskService, 
        TeamService $teamService
        )
        {
            $this->taskService = $taskService;
            $this->teamService = $teamService;
        }

    public function homePage(Request $request): JsonResponse
    {
        $user = $request->user();

        $tasks = $this->taskService->maisrecentes($user->id);
        $teams = $this->teamService->teamForUser($user->id);
        $teamCount = $this->teamService->countTeamUsers($user->id);

        return response()->json([
            'teams' => $teams,
            'tasks' => $tasks,
            'team_count' => $teamCount,
        ]);
    }
}
