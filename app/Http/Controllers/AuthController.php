<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use App\DTO\Login\LoginDTO;

use App\Http\Requests\Login\LoginRequest;

use App\Services\User\UserService;
use App\Models\User\User;
use App\Models\Task\Task;
use App\Http\Resources\User\UserResources;
use App\Http\Resources\Team\TeamResource;
use App\Http\Resources\Task\TaskResource;

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

            $user = new User([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $user->save();

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
            ], 201);
        }

    public function login(LoginRequest $request): JsonResponse
        {
            $validated = $request->validated();

            $loginDTO = new LoginDTO($validated['email'], $validated['password']);
            $successDTO = $this->userService->authenticate($loginDTO);

            if (!$successDTO) {
                return response()->json(['message' => 'Credenciais inválidas'], 401);
            }

            $user = $successDTO->user;
            $teamsCacheKey = "user:{$user->id}:teams";
            $tasksCacheKey = "user:{$user->id}:tasks";

            $teams = Cache::store('redis')->remember($teamsCacheKey, now()->addMinutes(10), function () use ($user) {
                return TeamResource::collection($user->teams()->get())->resolve();
            });

            $tasks = Cache::store('redis')->remember($tasksCacheKey, now()->addMinutes(5), function () use ($user) {
                return TaskResource::collection(
                    Task::query()->where('assigned_user_id', $user->id)->latest()->get()
                )->resolve();
            });

            return response()->json([
                'token' => $successDTO->token,
                'user' => new UserResources($user),
                'teams' => $teams,
                'tasks' => $tasks,
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
