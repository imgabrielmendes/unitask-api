<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Lista projetos dos times do usuário autenticado.
     *
     * Requer autenticação via Sanctum.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $projects = Project::query()
            ->whereIn('team_id', $user->teams()->pluck('teams.id'))
            ->get();

        return response()->json($projects);
    }

    /**
     * Cria um projeto em um time que o usuário participa.
     *
     * Requer autenticação via Sanctum.
     *
     * Body:
     * - name: string (obrigatório)
     * - description: string|null (opcional)
     * - team_id: int (obrigatório)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'team_id' => ['required', 'integer', 'exists:teams,id'],
        ]);

        if (!$user->teams()->whereKey($data['team_id'])->exists()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $project = Project::create($data);

        return response()->json($project, 201);
    }

    /**
     * Mostra um projeto (somente se o usuário estiver no time do projeto).
     *
     * Requer autenticação via Sanctum.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Project $project)
    {
        $user = $request->user();

        if (!$user->teams()->whereKey($project->team_id)->exists()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($project);
    }

    /**
     * Atualiza um projeto (somente se o usuário estiver no time do projeto).
     *
     * Requer autenticação via Sanctum.
     *
     * Body:
     * - name: string (opcional)
     * - description: string|null (opcional)
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Project $project)
    {
        $user = $request->user();

        if (!$user->teams()->whereKey($project->team_id)->exists()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project->update($data);

        return response()->json($project);
    }

    /**
     * Remove um projeto (somente se o usuário estiver no time do projeto).
     *
     * Requer autenticação via Sanctum.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Project $project)
    {
        $user = $request->user();

        if (!$user->teams()->whereKey($project->team_id)->exists()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $project->delete();

        return response()->json(null, 204);
    }
}
