<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Ability;
use App\Models\Task;
use App\Repositories\TasksRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function __construct(private readonly TasksRepository $tasksRepository)
    {
    }

    public function getTasksPaginated(array $paging): LengthAwarePaginator
    {
        $userId = Auth::user()->tokenCan(Ability::VIEW_ALL_TASKS->value) ? null : Auth::id();

        return $this->tasksRepository->getTasksPaginated($paging, $userId);
    }

    public function getTaskById(int $taskId): Task
    {
        return $this->tasksRepository->getTaskById($taskId);
    }

    public function createTask(array $data): Task
    {
        $data['user_id'] = $data['user_id'] ?? Auth::id();

        return $this->tasksRepository->createTask($data);
    }

    public function updateTask(int $taskId, array $data): Task
    {
        return $this->tasksRepository->updateTask($taskId, $data);
    }

    public function deleteTask(int $taskId): int
    {
        return $this->tasksRepository->deleteTask($taskId);
    }
}
