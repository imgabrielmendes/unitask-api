<?php

namespace App\Services\User;

use App\DTO\Login\LoginDTO;
use App\DTO\User\SuccessLoginDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Autentica um usuário com email e senha.
     * Retorna SuccessLoginDTO com token e model User, ou null se inválido.
     */
    public function authenticate(LoginDTO $dto): ?SuccessLoginDTO
    {
        $user = User::where('email', $dto->email)->first();

        if (!$user || !Hash::check($dto->password, $user->password)) {
            return null;
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return new SuccessLoginDTO(
            token: $token,
            user: $user,
        );
    }
}
