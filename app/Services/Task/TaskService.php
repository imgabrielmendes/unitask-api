<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\User;
use App\Http\Resources\TaskResource;

class TaskService
{
    private Task $model;

    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    public function create(User $user, array $data): Task
    {
        if (empty($data['assigned_user_id'])) {
            $data['assigned_user_id'] = $user->id;
        }

        $task = Task::create($data);
        return $task;
    }

    /**
     * Tarefas com estrela
     *
     * @return void
     */
    public function taskWithStars()
    {
        return $this->model::where('status', 'starred')->get();
    }

    /**
     * Tarefas com comentários
     *
     * @param [type] $id
     * @return void
     */
    public function taskWithComments($id)
    {
        return $this->model::with('comments')->where('id', $id)->get();
    }

    /**
     * Tarefas mais recentes
     *
     * @return void
     */
    public function maisrecentes($userId)
    {
        return  $this->model::orderBy('created_at', 'desc')->where('assigned_user_id', $userId)->get();
    }

    /**
     * Tarefas por time
     *
     * @param [type] $teamId
     * @return void
     */
    public function taskForTeam($teamId)
    {
        return  $this->model::where('team_id', $teamId)->get();
    }

    /**
     * Lista todas as tarefas de um usuário.
     */
    public function listUserTasks(User $user)
    {
        return $this->model::assignedTo($user->id)->get();
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
}
