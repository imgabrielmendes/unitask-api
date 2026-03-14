<?php

namespace App\DTO\Task;

use Carbon\Carbon;

// use App\DTO\AbstractDTO;
// use App\DTO\DTOInterface;

/**
 * DTO para Tarefas
 * Define os campos necessários para criar ou atualizar uma tarefa.
 * Inclui o ID do usuário para garantir que a tarefa seja atribuída corretamente
 * e para validar as permissões de criação/atualização.
 */
abstract class TaskDTO {
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $status,

        public readonly string $created_for,
        public readonly string $assigned_user_id,

        public readonly string $created_at,
    ) {}
}

/**
 * DTO para Criação de Tarefa
 * ID E TÍTULO OBRIGATÓRIOS
 */
class CreateTaskDTO extends TaskDTO {
    public function __construct(
        string $title,
        public int $userId,
            ?string $description = null,
            ?Carbon $dueAt = null,
    ) 
    
    {parent::__construct($title, $description, $dueAt);}
}

/**
 * DTO para Atualização de Tarefa
 * ID OBRIGATÓRIO
 */
class UpdateTaskDTO extends TaskDTO {
    public function __construct(
        public int $id,
            ?string $title = null,
            ?string $description = null,
            ?Carbon $dueAt = null,
    ) 
    {
        parent::__construct($title ?? '', $description, $dueAt);
    }
}

/**
 * DTO para Deletar uma tarefa
 * 
 */
class DeleteTaskDTO extends TaskDTO{
    public function __construct(
        public int $id,
        public int $userId
    ) 
        {
            parent::__construct( '', '', '');
        }
}
