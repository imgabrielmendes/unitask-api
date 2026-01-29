<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use App\Http\Resources\TaskResource;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $tasks = Task::getTaskforIdUser(
            $request->user()->id);
        return response()->json(TaskResource::collection($tasks));
    }

    public static function store(Request $request): JsonResponse
    {
        // O Request já validou os dados e checou se o usuário está no time.
        // Agora só passamos para o serviço criar.
        $task = Task::createTask(
            $request->user(),
            $request->all()
        );

        return response()->json(new TaskResource($task), 201);
    }


    public function show(Task $task): JsonResponse
    {
        // A autorização já foi feita pelo authorizeResource no construtor
        return response()->json(new TaskResource($task));
    }

    public static function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $updatedTask = Task::updateTask(
            $task, 
            $request->validated()
        );

        return response()->json(new TaskResource($updatedTask));
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->taskService->deleteTask($task);

        return response()->json(null, 204);
    }
}