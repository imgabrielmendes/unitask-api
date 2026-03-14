<?php


namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

use App\Models\Task\Task;
use App\Services\Task\TaskService;
use App\Http\Resources\Task\TaskResource;
use App\Http\Requests\Task\TaskRequest;

class TaskController extends Controller
{
    private TaskService $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

    public function index(TaskRequest $request): JsonResponse
    {
        $tasks = $this->service->listUserTasks($request->user());
        return response()->json(TaskResource::collection($tasks));
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $task = $this->service->create(
            $request->user(),
            $request->validated()
        );

        return response()->json(new TaskResource($task), 201);
    }

    public function show(Task $task): JsonResponse
    {
        return response()->json(new TaskResource($task));
    }

    public function update(TaskRequest $request, Task $task): JsonResponse
    {
        $updatedTask = $this->service->updateTask(
            $task,
            $request->validated()
        );
        return response()->json(new TaskResource($updatedTask));
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->service->deleteTask($task);
        return response()->json(null, 204);
    }

}