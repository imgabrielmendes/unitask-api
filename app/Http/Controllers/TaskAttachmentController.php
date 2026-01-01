<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;

class TaskAttachmentController extends Controller
{
    /**
     * Lista anexos de uma tarefa.
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

        return response()->json(TaskAttachment::where('task_id', $task->id)->get());
    }

    /**
     * Cria um anexo (metadados) para uma tarefa.
     *
     * Requer autenticação via Sanctum.
     * Regra de acesso: somente o usuário atribuído à tarefa.
     *
     * Body:
     * - filename: string (obrigatório)
     * - filepath: string (obrigatório)
     * - filetype: string|null (opcional)
     * - filesize: int|null (opcional)
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
            'filename' => ['required', 'string', 'max:255'],
            'filepath' => ['required', 'string', 'max:1024'],
            'filetype' => ['nullable', 'string', 'max:255'],
            'filesize' => ['nullable', 'integer', 'min:0'],
        ]);

        $attachment = TaskAttachment::create([
            'task_id' => $task->id,
            'filename' => $data['filename'],
            'filepath' => $data['filepath'],
            'filetype' => $data['filetype'] ?? null,
            'filesize' => $data['filesize'] ?? null,
        ]);

        return response()->json($attachment, 201);
    }

    /**
     * Remove um anexo de uma tarefa.
     *
     * Requer autenticação via Sanctum.
     * Regra de acesso: somente o usuário atribuído à tarefa.
     *
     * @param  \App\Models\Task  $task
     * @param  \App\Models\TaskAttachment  $attachment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Task $task, TaskAttachment $attachment)
    {
        $user = $request->user();

        if ($task->id !== $attachment->task_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if ($task->assigned_user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $attachment->delete();

        return response()->json(null, 204);
    }
}
