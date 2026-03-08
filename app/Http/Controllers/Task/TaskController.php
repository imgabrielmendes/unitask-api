<?php


namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

use App\Models\Task;
use App\Services\Task\TaskService;
use App\Http\Resources\TaskResource;
use App\Http\Requests\Task\TaskRequest;

class TaskController extends Controller
{
    private TaskService $service;
    private TaskResource $resource;

    public function __construct(TaskService $service, TaskResource $resource)
    {
        $this->service = $service;
        $this->resource = $resource;
    }

    public function index(TaskRequest $request): JsonResponse
    {
        $tasks = $this->service->listUserTasks($request->user());
        return response()->json($this->resource::collection($tasks));
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
        return response()->json($this->resource::collection($task));
    }

    public function update(TaskRequest $request, Task $task): JsonResponse
    {
        $updatedTask = $this->service->updateTask(
            $task,
            $request->validated()
        );
        return response()->json($this->resource::collection($updatedTask));
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->service->deleteTask($task);
        return response()->json(null, 204);
    }

}