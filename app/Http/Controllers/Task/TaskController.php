<?php


namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;

use App\Models\Task;
use App\Services\TaskService;
use App\Http\Resources\TaskResource;
use App\Http\Requests\Task\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request): JsonResponse
    {
        $tasks = $this->taskService->listUserTasks($request->user());
        return response()->json(TaskResource::collection($tasks));
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask(
            $request->user(),
            $request->validated()
        );

        return response()->json(new TaskResource($task), 201);
    }

    public function show(Task $task): JsonResponse
    {
        return response()->json(new TaskResource($task));
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $updatedTask = $this->taskService->updateTask(
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