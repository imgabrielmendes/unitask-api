<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Team\Team;
use Illuminate\Http\Request;
use App\Http\Resources\Team\TeamResource;
use Illuminate\Support\Facades\Cache;

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
        $cacheKey = "user:{$user->id}:teams";

        $teams = Cache::store('redis')->remember($cacheKey, now()->addMinutes(10), function () use ($user) {
            return TeamResource::collection($user->teams()->get())->resolve();
        });

        return response()->json($teams);
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

        Cache::store('redis')->forget("user:{$user->id}:teams");

        return response()->json(new TeamResource($team), 201);
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

        return response()->json(new TeamResource($team->load(['users'])));
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

        $teamUserIds = $team->users()->pluck('users.id')->all();
        $team->update($data);

        foreach ($teamUserIds as $teamUserId) {
            Cache::store('redis')->forget("user:{$teamUserId}:teams");
        }

        return response()->json(new TeamResource($team));
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

        $teamUserIds = $team->users()->pluck('users.id')->all();
        $team->delete();

        foreach ($teamUserIds as $teamUserId) {
            Cache::store('redis')->forget("user:{$teamUserId}:teams");
        }

        return response()->json(null, 204);
    }

    
}
