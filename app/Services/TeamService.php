<?php

namespace App\Services;

use App\Models\Team;
use App\Http\Resources\TeamResource;

class TeamService
{

    protected TeamService $teamService;

    /**
     * Cria um time e adiciona o usuário como membro.
     *
     * @param  array  $data
     * @param  int  $userId
     * @return \App\Models\Team
     */
    public function createTeam(array $data, int $userId): TeamResource
    {
        $team = Team::create($data);
        $team->users()->attach($userId);
        return new TeamResource($team);
    }

    /**
     * Undocumented function
     *
     * @param [type] $user
     * @return void
     */
    public function listUserTeams($user)
    {
        return $user->teams;
    }

    /**
     * Undocumented function
     *
     * @param [type] $teamId
     * @return void
     */
    public function countTeamUsers($teamId)
    {
        $team = Team::find($teamId);
        return $team ? $team->users()->count() : 0;
    }

    /**
     * Undocumented function
     *
     * @param [type] $userId
     * @return void
     */
    public function teamForUser($userId)
    {
        return TeamResource::collection(Team::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get());
    }

}