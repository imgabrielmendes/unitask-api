<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\DTO\Login\LoginDTO;

use App\Services\User\UserService;
use App\Http\Resources\UserResources;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    
        public function __construct(
            private UserService $userService 
        ) {}

        public function register(Request $request)
        {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
            ], 201);
        }

    public function login(LoginRequest $request): JsonResponse
        {

            $loginDTO = new LoginDTO($request->email, $request->password);
            $successDTO = $this->userService->authenticate($loginDTO);

            if (!$successDTO) {
                return response()->json(['message' => 'Credenciais inválidas'], 401);
            }

            return response()->json([
                'token' => $successDTO->token,
                'user' => new UserResources($successDTO->user),
            ]);
        }

        /**
         * Faz logout revogando o token atual do Sanctum.
         *
         * Requer autenticação via Sanctum.
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function logout(Request $request)
        {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Não autenticado'], 401);
            }

            $token = $user->currentAccessToken();

            if ($token) {
                $token->delete();
            }

            return response()->json(['message' => 'Logout efetuado com sucesso']);
        }

        

}
