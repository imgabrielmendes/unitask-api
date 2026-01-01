<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    /**
     * Lista comentários de uma tarefa.
     *
     * Requer autenticação via Sanctum.
     * Regra de acesso: somente o usuário atribuído à tarefa.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Task $task)
    {
        $user = $request->user();

        if ($task->assigned_user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $comments = TaskComment::query()
            ->where('task_id', $task->id)
            ->with(['user:id,name,email'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($comments);
    }

    /**
     * Cria um comentário em uma tarefa.
     *
     * Requer autenticação via Sanctum.
     * Regra de acesso: somente o usuário atribuído à tarefa.
     *
     * Body:
     * - comment: string (obrigatório)
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Task $task)
    {
        $user = $request->user();

        if ($task->assigned_user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'comment' => ['required', 'string'],
        ]);

        $comment = TaskComment::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'comment' => $data['comment'],
        ]);

        return response()->json($comment->load(['user:id,name,email']), 201);
    }

    /**
     * Atualiza um comentário (somente o autor pode editar).
     *
     * Requer autenticação via Sanctum.
     *
     * Body:
     * - comment: string (obrigatório)
     *
     * @param  \App\Models\Task  $task
     * @param  \App\Models\TaskComment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Task $task, TaskComment $comment)
    {
        $user = $request->user();

        if ($task->id !== $comment->task_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if ($comment->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'comment' => ['required', 'string'],
        ]);

        $comment->update(['comment' => $data['comment']]);

        return response()->json($comment->load(['user:id,name,email']));
    }

    /**
     * Remove um comentário (somente o autor pode remover).
     *
     * Requer autenticação via Sanctum.
     *
     * @param  \App\Models\Task  $task
     * @param  \App\Models\TaskComment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Task $task, TaskComment $comment)
    {
        $user = $request->user();

        if ($task->id !== $comment->task_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if ($comment->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $comment->delete();

        return response()->json(null, 204);
    }
}
