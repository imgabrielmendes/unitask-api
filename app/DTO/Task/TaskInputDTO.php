<?php

namespace App\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator;

class TaskInputDTO extends AbstractDTO implements interfaceDTO
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description = null,
        public readonly ?int $teamId = null,
        public readonly ?int $assignedUserId = null,
        public readonly string $status = 'pending',
        public readonly ?string $dueDate = null,
    ) {
    }



}