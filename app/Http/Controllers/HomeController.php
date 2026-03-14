<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Services\Team\TeamService;
use App\Services\Task\TaskService;
use App\Http\Resources\User\UserResources;

class HomeController extends Controller
{
    protected TaskService $taskService;
    protected TeamService $teamService;
    protected UserResources $userResource;

    public function __construct(
        TaskService $taskService, 
        TeamService $teamService,
        )
        {
            $this->taskService = $taskService;
            $this->teamService = $teamService;
        }

        /**
         * O que uma página Home precisa retornar:
         * 1 - Tarefas mais recentes
         * 2 - Times do usuário
         * 3 - Notificações
         * 4 - 
         *
         * @param Request $request
         * @return JsonResponse
         */
    public function homePage(Request $request): JsonResponse
    {

        $user = UserResources::make($request->user());
        $tasks = $this->taskService->maisrecentes($user->id);
        $teams = $this->teamService->teamForUser($user->id);
        $teamCount = $this->teamService->countTeamUsers($user->id);

        return response()->json([
            'data' => [
                'user' => $user,
                'tasks' => $tasks,
                'teams' => $teams,
                'team_count' => $teamCount,
            ],
        ]);
    }
}
