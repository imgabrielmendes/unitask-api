<?php

namespace App\DTO;

use Illuminate\Contracts\Validation\Validator;

class TaskDTO extends AbstractDTO implements interfaceDTO
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description = null,
        public readonly ?int $teamId = null,
        public readonly ?int $assignedUserId = null,
        public readonly string $status = 'pending',
        public readonly ?string $dueDate = null,
    ) {}



    public function validator(): Validator
    {
       return validator($this->toArray(), $this->rules(), $this->messages());
    }

    public function validate(): array
    {
       return $this->validator();
    }
    
    
}