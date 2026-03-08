<?php

namespace App\DTO\Login;

/**
 * DTO simples para transportar as credenciais de login.
 */
class LoginDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}
}


