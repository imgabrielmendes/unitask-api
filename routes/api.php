<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskAttachmentController;
use App\Http\Controllers\MemberTeamController;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Http\Request;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    /**
     * Teams
     *
     * CRUD de times do usuário autenticado.
     * - GET /api/teams
     * - POST /api/teams
     * - GET /api/teams/{team}
     * - PUT/PATCH /api/teams/{team}
     * - DELETE /api/teams/{team}
     */
    Route::apiResource('teams', TeamController::class);


    Route::apiResource('member', MemberTeamController::class);

    /**
     * Projects
     *
     * CRUD de projetos de times que o usuário participa.
     * - GET /api/projects
     * - POST /api/projects
     * - GET /api/projects/{project}
     * - PUT/PATCH /api/projects/{project}
     * - DELETE /api/projects/{project}
     */
    Route::apiResource('projects', ProjectController::class);

    /**
     * Tasks
     *
     * CRUD de tarefas atribuídas ao usuário autenticado.
     * - GET /api/tasks
     * - POST /api/tasks
     * - GET /api/tasks/{task}
     * - PUT/PATCH /api/tasks/{task}
     * - DELETE /api/tasks/{task}
     */
    Route::apiResource('tasks', TaskController::class);

    /**
     * Comments (aninhadas em Tasks)
     *
     * Comentários de uma tarefa (somente se a tarefa for do usuário).
     * - GET /api/tasks/{task}/comments
     * - POST /api/tasks/{task}/comments
     * - PUT /api/tasks/{task}/comments/{comment}
     * - DELETE /api/tasks/{task}/comments/{comment}
     */
    Route::get('tasks/{task}/comments', [TaskCommentController::class, 'index']);
    Route::post('tasks/{task}/comments', [TaskCommentController::class, 'store']);
    Route::put('tasks/{task}/comments/{comment}', [TaskCommentController::class, 'update']);
    Route::delete('tasks/{task}/comments/{comment}', [TaskCommentController::class, 'destroy']);

    /**
     * Attachments (aninhadas em Tasks)
     *
     * Anexos de uma tarefa (somente se a tarefa for do usuário).
     * Obs: endpoints atuais trabalham com metadados (filename/filepath/etc).
     * - GET /api/tasks/{task}/attachments
     * - POST /api/tasks/{task}/attachments
     * - DELETE /api/tasks/{task}/attachments/{attachment}
     */
    Route::get('tasks/{task}/attachments', [TaskAttachmentController::class, 'index']);
    Route::post('tasks/{task}/attachments', [TaskAttachmentController::class, 'store']);
    Route::delete('tasks/{task}/attachments/{attachment}', [TaskAttachmentController::class, 'destroy']);
});

/**
 * GET /api/user
 * Retorna o usuário autenticado.
 *
 * Requer autenticação via Sanctum.
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Broadcast::routes();