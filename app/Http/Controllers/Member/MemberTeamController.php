<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Member\Member;
use App\Models\Team\Team;
use Illuminate\Support\Facades\Cache;

class MemberTeamController extends Controller
{

    /**
     * Adiciona um usuário a um time.
     *
     * Requer autenticação via Sanctum.
     * Regra de acesso: somente membros do time podem adicionar outros membros.
     *
     * Body:
     * - user_id: integer (obrigatório, ID do usuário a ser adicionado)
     * - team_id: integer (obrigatório, ID do time ao qual o usuário será adicionado)
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Team $team, Member $member)
    {
        $user = $request->user();

        $data = request()->validate([
            'user_id' => ['required', 'exists:users,id'],
            'team_id' => ['required', 'exists:teams,id'],
        ]);

        $team = Team::query()->findOrFail($data['team_id']);

        if (!$team->users()->whereKey($user->id)->exists()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $team->users()->attach($data['user_id']);

        Cache::store('redis')->forget("user:{$user->id}:teams");
        Cache::store('redis')->forget("user:{$data['user_id']}:teams");

        return response()->json(['message' => 'User added to team'], 201);
    }

    /**
     * Remove o usuário autenticado de um time.
     *
     * Requer autenticação via Sanctum.
     * Regra de acesso: somente membros do time podem se remover.
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

        $team->users()->detach($user->id);

        Cache::store('redis')->forget("user:{$user->id}:teams");

        return response()->json(['message' => 'Removed from team'], 200);
    }

}
