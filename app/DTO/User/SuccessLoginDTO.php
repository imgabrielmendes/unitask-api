<?php

namespace App\DTO\User;

use App\Models\User;

/**
 * DTO retornado após autenticação bem-sucedida.
 * Carrega o token Sanctum e o model User autenticado.
 */
class SuccessLoginDTO
{
    public function __construct(
        public readonly string $token,
        public readonly User $user,
    ) {}
}