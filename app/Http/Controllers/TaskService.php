<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    /**
     * Lista tarefas de um usuário.
     */
    public function listUserTasks(User $user)
    {
        return Task::query()
            ->where('assigned_user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Cria a tarefa com a lógica de atribuição.
     */
    public function createTask(User $user, array $data): Task
    {
        // Regra de negócio: Tratamento dos dados antes de salvar
        $data = $this->prepareDataForCreation($data, $user);

        return Task::create($data);
    }

    /**
     * Atualiza a tarefa.
     */
    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    /**
     * Deleta a tarefa.
     */
    public function deleteTask(Task $task): void
    {
        $task->delete();
    }

    // --- MÉTODOS PRIVADOS (Auxiliares Internos) ---

    /**
     * Prepara o array de dados para criação.
     * Define o assigned_user_id padrão se não for enviado.
     */
    private function prepareDataForCreation(array $data, User $currentUser): array
    {
        // Se 'assigned_user_id' não veio ou é null, usa o ID do usuário atual
        if (empty($data['assigned_user_id'])) {
            $data['assigned_user_id'] = $currentUser->id;
        }

        // Poderíamos adicionar outras lógicas aqui, ex: calcular data de entrega padrão
        // if (empty($data['due_date'])) { $data['due_date'] = now()->addDays(7); }

        return $data;
    }
}