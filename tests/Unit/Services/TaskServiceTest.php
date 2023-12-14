<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TasksRepository;
use App\Services\TaskService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TaskServiceTest extends TestCase
{
    private TaskService $taskService;
    private MockObject $tasksRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tasksRepositoryMock = $this->createMock(TasksRepository::class);
        $this->taskService = new TaskService($this->tasksRepositoryMock);
    }

    /**
     * @test
     * @covers \App\Services\TaskService::getTasksPaginated
     */
    public function test_get_tasks_paginated(): void
    {
        $userId = 1;
        $userMock = $this->createMock(User::class);
        $userMock->expects($this->once())->method('tokenCan')->willReturn(false);

        Auth::shouldReceive('user')->andReturn($userMock);
        Auth::shouldReceive('id')->andReturn($userId);
        $paging = ['page' => 1, 'limit' => 10];

        $this->tasksRepositoryMock
            ->expects($this->once())
            ->method('getTasksPaginated')
            ->with($paging, $userId)
            ->willReturn(new LengthAwarePaginator([], 0, 10));

        $result = $this->taskService->getTasksPaginated($paging);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    /**
     * @test
     * @covers \App\Services\TaskService::getTaskById
     */
    public function it_should_return_task_by_id(): void
    {
        $taskId = 1;
        $task = new Task();

        $this->tasksRepositoryMock
            ->expects($this->once())
            ->method('getTaskById')
            ->with($taskId)
            ->willReturn($task);

        $result = $this->taskService->getTaskById($taskId);

        $this->assertInstanceOf(Task::class, $result);
    }

    /**
     * @test
     * @covers \App\Services\TaskService::createTask
     */
    public function it_should_create_new_task(): void
    {
        $data = ['title' => 'Test Task', 'description' => 'Test Description', 'user_id' => 1];
        Auth::shouldReceive('id')->andReturn(1);

        $this->tasksRepositoryMock
            ->expects($this->once())
            ->method('createTask')
            ->with($data)
            ->willReturn(new Task($data));

        $result = $this->taskService->createTask($data);

        $this->assertInstanceOf(Task::class, $result);
    }

    /**
     * @test
     * @covers \App\Services\TaskService::updateTask
     */
    public function it_should_update_task(): void
    {
        $taskId = 1;
        $data = ['title' => 'Updated Task Title'];

        $this->tasksRepositoryMock
            ->expects($this->once())
            ->method('updateTask')
            ->with($taskId, $data)
            ->willReturn(new Task($data));

        $result = $this->taskService->updateTask($taskId, $data);

        $this->assertInstanceOf(Task::class, $result);
    }

    /**
     * @test
     * @covers \App\Services\TaskService::deleteTask
     */
    public function it_should_delete_task(): void
    {
        $taskId = 1;

        $this->tasksRepositoryMock
            ->expects($this->once())
            ->method('deleteTask')
            ->with($taskId)
            ->willReturn(1);

        $result = $this->taskService->deleteTask($taskId);

        $this->assertEquals(1, $result);
    }
}
