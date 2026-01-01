<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
        /**
         * Cria um usuário e retorna um token do Sanctum.
         *
         * Body:
         * - name: string (obrigatório)
         * - email: string (obrigatório, único)
         * - password: string (obrigatório)
         * - password_confirmation: string (opcional, se enviado será validado)
         *
         * @return \Illuminate\Http\JsonResponse
         */
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

    /**
     * Autentica um usuário e retorna um token do Sanctum.
     *
     * Body:
     * - email: string (obrigatório)
     * - password: string (obrigatório)
     *
     * @return \Illuminate\Http\JsonResponse
     */
        public function login(Request $request)
        {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Credenciais inválidas'], 401);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
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
