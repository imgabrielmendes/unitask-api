<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Lista os times do usuário autenticado.
     *
     * Requer autenticação via Sanctum.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json($user->teams()->get());
    }

    /**
     * Cria um time e adiciona o usuário autenticado como membro.
     *
     * Requer autenticação via Sanctum.
     *
     * Body:
     * - name: string (obrigatório)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $team = Team::create($data);
        $team->users()->attach($user->id);

        return response()->json($team, 201);
    }

    /**
     * Mostra um time específico (somente se o usuário for membro).
     *
     * Requer autenticação via Sanctum.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Team $team)
    {
        $user = $request->user();

        if (!$team->users()->whereKey($user->id)->exists()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($team->load(['users']));
    }

    /**
     * Atualiza um time (somente se o usuário for membro).
     *
     * Requer autenticação via Sanctum.
     *
     * Body:
     * - name: string (opcional)
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Team $team)
    {
        $user = $request->user();

        if (!$team->users()->whereKey($user->id)->exists()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
        ]);

        $team->update($data);

        return response()->json($team);
    }

    /**
     * Remove um time (somente se o usuário for membro).
     *
     * Requer autenticação via Sanctum.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Team $team)
    {
        $user = $request->user();

        if (!$team->users()->whereKey($user->id)->exists()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $team->delete();

        return response()->json(null, 204);
    }

    
}
