<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class TasksRepository
{
    public function getTasksPaginated(array $paging, ?int $userId = null): LengthAwarePaginator
    {
        $query = Task::query();

        if ($paging['search']) {
            $query->orWhere('title', 'like', '%' . $paging['search'] . '%');
            $query->orWhere('description', 'like', '%' . $paging['search'] . '%');
            $query->orWhere('status', 'like', '%' . $paging['search'] . '%');
        }

        if ($paging['sort']) {
            foreach ($paging['sort'] as $item) {
                $query->orderBy($item['column'], $item['direction']);
            }
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->paginate($paging['limit'], ['*'], 'page', $paging['page']);
    }

    public function getTaskById(int $taskId): Task
    {
        return Task::findOrFail($taskId);
    }

    public function createTask(array $data): Task
    {
        $user = Task::create($data);

        return $user->refresh();
    }

    public function updateTask(int $taskId, array $data): Task
    {
        $user = Task::findOrFail($taskId);
        $user->update($data);

        return $user->refresh();
    }

    public function deleteTask(int $taskId): int
    {
        return Task::destroy($taskId);
    }
}
