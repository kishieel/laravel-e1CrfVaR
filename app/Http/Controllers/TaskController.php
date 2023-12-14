<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use App\Utils\Paginable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

use function response;

class TaskController extends Controller
{
    use Paginable;

    public function __construct(private readonly TaskService $taskService)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Task::class);

        $paging = $this->getPaging($request, ['title', 'description', 'status']);
        $tasks = $this->taskService->getTasksPaginated($paging);

        return TaskResource::collection($tasks);
    }

    public function store(TaskCreateRequest $request): TaskResource
    {
        $this->authorize('create', Task::class);
        $data = $request->validated();
        $task = $this->taskService->createTask($data);

        return TaskResource::make($task);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task = $this->taskService->getTaskById($task->id);

        return TaskResource::make($task);
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $data = $request->validated();
        $task = $this->taskService->updateTask($task->id, $data);

        return TaskResource::make($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $this->taskService->deleteTask($task->id);

        return response()->noContent();
    }
}
