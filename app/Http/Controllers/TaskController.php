<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Lista tarefas atribuídas ao usuário autenticado.
     *
     * Requer autenticação via Sanctum.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $tasks = Task::query()
            ->where('assigned_user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($tasks);
    }

    /**
     * Cria uma tarefa em um time que o usuário participa.
     *
     * Requer autenticação via Sanctum.
     *
     * Body:
     * - title: string (obrigatório)
     * - description: string|null (opcional)
     * - team_id: int (obrigatório)
     * - assigned_user_id: int|null (opcional; default: usuário autenticado)
     * - status: pending|in_progress|completed (opcional)
     * - due_date: date (opcional)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'team_id' => ['required', 'integer', 'exists:teams,id'],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'status' => ['nullable', 'in:pending,in_progress,completed'],
            'due_date' => ['nullable', 'date'],
        ]);

        if (!$user->teams()->whereKey($data['team_id'])->exists()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Se não informar, atribui ao usuário logado
        $data['assigned_user_id'] = $data['assigned_user_id'] ?? $user->id;

        $task = Task::create($data);

        
        return response()->json($task, 201);

    }

    /**
     * Retorna uma tarefa específica, somente se ela estiver atribuída ao usuário.
     *
     * Requer autenticação via Sanctum.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Task $task)
    {
        $user = $request->user();

        if ($task->assigned_user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($task);
    }

    /**
     * Atualiza uma tarefa, somente se ela estiver atribuída ao usuário.
     *
     * Requer autenticação via Sanctum.
     *
     * Body:
     * - title: string (opcional)
     * - description: string|null (opcional)
     * - status: pending|in_progress|completed (opcional)
     * - due_date: date|null (opcional)
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Task $task)
    {
        $user = $request->user();

        if ($task->assigned_user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:pending,in_progress,completed'],
            'due_date' => ['nullable', 'date'],
        ]);

        $task->update($data);

        return response()->json($task);
    }

    /**
     * Remove uma tarefa, somente se ela estiver atribuída ao usuário.
     *
     * Requer autenticação via Sanctum.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Task $task)
    {
        $user = $request->user();

        if ($task->assigned_user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $task->delete();

        return response()->json(null, 204);
    }

    /**
     * Endpoint legado: lista tarefas do usuário autenticado.
     *
     * Observação: para uso REST prefira GET /api/tasks.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTaskforUser(){


    $user = Auth::user();   
  
    $userId = Auth::id();

    $task = Task::getTaskforIdUser($userId);


    return response()->json($task);

    }
}
