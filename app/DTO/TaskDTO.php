<?php

namespace App\DTO;

class TaskDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description = null,
        public readonly ?int $teamId = null,
        public readonly ?int $assignedUserId = null,
        public readonly string $status = 'pending',
        public readonly ?string $dueDate = null,
    ) {}

    // public static function fromRequest(array $data): self
    // {
    //     return new self(
    //         title: $data['title'],
    //         description: $data['description'] ?? null,
    //         teamId: $data['team_id'] ?? null,
    //         assignedUserId: $data['assigned_user_id'] ?? null,
    //         status: $data['status'] ?? 'pending',
    //         dueDate: $data['due_date'] ?? null,
    //     );
    // }
}