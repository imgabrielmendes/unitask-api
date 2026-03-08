<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\User;
use App\Http\Resources\TaskResource;

use App\DTO\Task\TaskDTO;
use App\DTO\Task\CreateTaskDTO;
use App\DTO\Task\UpdateTaskDTO;
use App\DTO\Task\DeleteTaskDTO;

class TaskService
{
    private Task $model;
    private TaskDTO $dto;

    public function __construct(Task $model, TaskDTO $dto)
    {
        $this->model = $model;
        $this->dto = $dto;
    }

    public function create(CreateTaskDTO $dto)
    {

        $task = $this->model::create(
            [
                'title' => $dto->title,
                'description' => $dto->description,
                'assigned_user_id' => $dto->userId,
                'due_date' => $dto->created_at,
            ]
        );

        return $task;

    }

    /**
     * Atualiza uma tarefa.
     */
    public function update(UpdateTaskDTO $dto)
    {
        $task = $this->model::findOrFail($dto->id);

        $task->update(
            [
                'title' => $dto->title,
                'description' => $dto->description,
            ]
        );

        return $task;
    }

    /**
     * Deleta uma tarefa.
     */
    public function deleteTask(DeleteTaskDTO $dto)
    {
        $user_permission = $this->model::where('id', $dto->id)->where('assigned_user_id', $dto->userId)->exists();

        if (!$user_permission) {
            throw new \InvalidArgumentException('User not authorized to delete this task.');
        }

        $task = $this->model::findOrFail($dto->id);
        $task->delete();
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

}
