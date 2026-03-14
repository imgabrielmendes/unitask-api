<?php

namespace App\DTO\Team;

use App\DTO\AbstractDTO;

class TeamDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $city,
        public readonly string $stadium
    ) {}
}