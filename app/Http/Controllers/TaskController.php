<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use App\Http\Resources\TaskResource;
use App\Http\Requests\StoreTaskRequest; // Request customizado
use App\Http\Requests\UpdateTaskRequest; // Assuma que criamos este também
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    protected $taskService;

    // Injeção de Dependência do Service
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
        
        // Garante que a Policy de Task seja aplicada automaticamente aos métodos resource
        // Isso substitui os ifs manuais: if ($task->user_id !== $user->id)
        $this->authorizeResource(Task::class, 'task'); 
    }

    public function index(Request $request): JsonResponse
    {
        // O Controller pede os dados ao serviço
        $tasks = $this->taskService->listUserTasks($request->user());

        return response()->json(TaskResource::collection($tasks));
    }

    // Usamos StoreTaskRequest em vez de Request genérico
    public function store(StoreTaskRequest $request): JsonResponse
    {
        // O Request já validou os dados e checou se o usuário está no time.
        // Agora só passamos para o serviço criar.
        $task = $this->taskService->createTask(
            $request->user(),
            $request->validated()
        );

        return response()->json(new TaskResource($task), 201);
    }

    public function show(Task $task): JsonResponse
    {
        // A autorização já foi feita pelo authorizeResource no construtor
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