<?php

namespace App\DTO;

class TeamDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $city,
        public readonly string $stadium,
    ) 
    {



    }
}