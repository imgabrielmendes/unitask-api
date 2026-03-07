<?php

namespace App\DTO\Task;

use App\DTO\AbstractDTO;
use App\DTO\interfaceDTO;


class TaskDTO extends AbstractDTO implements interfaceDTO
{
    public string $title;
    public string $description;
    public string $status;
    public string $due_date;

    public function __construct(string $title, string $description, string $status, string $due_date)
    {
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->due_date = $due_date;
    }



}