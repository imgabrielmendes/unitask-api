<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;

use App\Http\Resources\TaskResource;

class TaskService
{

    /**
     * Tarefas com estrela
     *
     * @return void
     */
    public function taskWithStars()
    {
        return  TaskResource::collection(Task::where('status', 'starred')->get());
    }

    /**
     * Tarefas com comentários
     *
     * @param [type] $id
     * @return void
     */
    public function taskWithComments($id)
    {
        return TaskResource::collection(Task::with('comments')->where($id)->get());
    }

    /**
     * Tarefas mais recentes
     *
     * @return void
     */
    public function maisrecentes($userId)
    {
        return  TaskResource::collection(Task::orderBy('created_at', 'desc')->where('assigned_user_id', $userId)->get());
    }

    /**
     * Tarefas por time
     *
     * @param [type] $teamId
     * @return void
     */
    public function taskForTeam($teamId)
    {
        return  TaskResource::collection(Task::where('team_id', $teamId)->get());
    }

    /**
     * Lista todas as tarefas de um usuário.
     */
    public function listUserTasks(User $user)
    {
        return Task::assignedTo($user->id)->get();
    }

    /**
     * Cria uma tarefa aplicando regras de negócio.
     */
    public function createTask(User $user, array $data): Task
    {
        $data = $this->prepareDataForCreation($data, $user);
        return Task::create($data);
    }

    /**
     * Atualiza uma tarefa.
     */
    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    /**
     * Deleta uma tarefa.
     */
    public function deleteTask(Task $task): void
    {
        $task->delete();
    }

    /**
     * Prepara os dados para criação da tarefa.
     */
    private function prepareDataForCreation(array $data, User $currentUser): array
    {
        if (empty($data['assigned_user_id'])) {
            $data['assigned_user   _id'] = $currentUser->id;
        }
        return $data;
    }
}
