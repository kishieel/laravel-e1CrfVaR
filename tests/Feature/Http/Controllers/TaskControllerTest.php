<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Enums\Ability;
use App\Enums\TaskStatus;
use App\Enums\UserType;
use App\Models\Task;
use App\Models\User;

use function fake;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::index
     */
    public function it_should_allow_to_list_own_tasks()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::VIEW_OWN_TASKS->value]);
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks');
        $response->assertOk();
        $response->assertJson([
            'data' => [
                0 => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'status' => $task->status->value,
                    'user_id' => $task->user_id,
                ],
            ],
        ]);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::index
     */
    public function it_should_allow_to_list_all_tasks()
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();
        Sanctum::actingAs($admin, [Ability::VIEW_ALL_TASKS->value]);
        Task::factory()->count(1)->create(['user_id' => $user->id]);
        Task::factory()->count(1)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks');
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                0 => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'user_id',
                ],
            ],
        ]);
        $response->assertJsonCount(2, 'data');
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::index
     */
    public function it_should_allow_to_list_tasks_with_search_criteria()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::VIEW_OWN_TASKS->value]);
        Task::factory()->create(['title' => 'Some example title', 'user_id' => $user->id]);
        $task = Task::factory()->create(['title' => 'Some other example title', 'user_id' => $user->id]);

        $response = $this->getJson('/api/tasks?search=other');
        $response->assertOk();
        $response->assertJson(['data' => [0 => ['id' => $task->id, ]], ]);
        $response->assertJsonCount(1, 'data');
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::index
     */
    public function it_should_allow_to_list_tasks_with_sort_criteria()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::VIEW_OWN_TASKS->value]);
        $tasks = [
            Task::factory()->create(['title' => 'AAA', 'description' => 'AAA', 'user_id' => $user->id]),
            Task::factory()->create(['title' => 'AAA', 'description' => 'BBB', 'user_id' => $user->id]),
            Task::factory()->create(['title' => 'BBB', 'description' => 'AAA', 'user_id' => $user->id]),
        ];

        $response = $this->getJson('/api/tasks?sort[]=title:desc&sort[]=description:asc');
        $response->assertOk();
        $response->assertJson([
            'data' => [
                0 => ['id' => $tasks[2]->id, ],
                1 => ['id' => $tasks[0]->id, ],
                2 => ['id' => $tasks[1]->id, ],
            ],
        ]);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::index
     */
    public function it_should_allow_to_paginate_tasks_with_limit_criteria()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::VIEW_OWN_TASKS->value]);
        $tasks = Task::factory()->count(4)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks?page=2&limit=2');
        $response->assertOk();
        $response->assertJson([
            'data' => [
                0 => ['id' => $tasks[2]->id, ],
                1 => ['id' => $tasks[3]->id, ],
            ],
        ]);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::index
     */
    public function it_should_deny_to_list_tasks_without_ability()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        Task::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks');
        $response->assertForbidden();
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::show
     */
    public function it_should_allow_to_view_own_tasks()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::VIEW_OWN_TASKS->value]);
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks/' . $task->id);
        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status->value,
                'user_id' => $task->user_id,
            ],
        ]);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::show
     */
    public function it_should_allow_to_view_all_tasks()
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();
        Sanctum::actingAs($admin, [Ability::VIEW_ALL_TASKS->value]);
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks/' . $task->id);
        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status->value,
                'user_id' => $task->user_id,
            ],
        ]);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::show
     */
    public function it_should_deny_to_view_tasks_without_ability()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks/' . $task->id);
        $response->assertForbidden();
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::store
     */
    public function it_should_allow_to_create_own_tasks()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::MODIFY_OWN_TASKS->value]);

        $request = $this->getTaskModifyRequest();
        $response = $this->postJson('/api/tasks', $request);
        $response->assertCreated();
        $response->assertJson([
            'data' => [
                'title' => $request['title'],
                'description' => $request['description'],
                'status' => $request['status'],
                'user_id' => $user->id,
            ],
        ]);

        $this->assertDatabaseCount('tasks', 1);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::store
     */
    public function it_should_allow_to_create_all_tasks()
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['type' => UserType::ADMIN]);
        Sanctum::actingAs($admin, [Ability::MODIFY_ALL_TASKS->value]);

        $request = $this->getTaskModifyRequest($user->id);
        $response = $this->postJson('/api/tasks', $request);
        $response->assertCreated();
        $response->assertJson([
            'data' => [
                'title' => $request['title'],
                'description' => $request['description'],
                'status' => $request['status'],
                'user_id' => $user->id,
            ],
        ]);

        $this->assertDatabaseCount('tasks', 1);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::store
     */
    public function it_should_deny_to_create_task_without_ability()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/tasks', $this->getTaskModifyRequest());
        $response->assertForbidden();
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::update
     */
    public function it_should_allow_to_update_own_tasks()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::MODIFY_OWN_TASKS->value]);
        $task = Task::factory()->create(['user_id' => $user->id, 'status' => TaskStatus::TODO]);

        $request = $this->getTaskModifyRequest();
        $response = $this->patchJson('/api/tasks/' . $task->id, $request);
        $response->assertOk();
        $response->assertJson([
            'data' => [
                'title' => $request['title'],
                'description' => $request['description'],
                'status' => $request['status'],
                'user_id' => $user->id,
            ],
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $request['title'],
            'description' => $request['description'],
            'status' => $request['status'],
            'user_id' => $user->id,
        ]);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::update
     */
    public function it_should_allow_to_update_all_tasks()
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['type' => UserType::ADMIN]);
        Sanctum::actingAs($admin, [Ability::MODIFY_ALL_TASKS->value]);
        $task = Task::factory()->create(['user_id' => $user->id, 'status' => TaskStatus::TODO]);

        $request = $this->getTaskModifyRequest($admin->id);
        $response = $this->patchJson('/api/tasks/' . $task->id, $request);
        $response->assertOk();
        $response->assertJson([
            'data' => [
                'title' => $request['title'],
                'description' => $request['description'],
                'status' => $request['status'],
                'user_id' => $admin->id,
            ],
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $request['title'],
            'description' => $request['description'],
            'status' => $request['status'],
            'user_id' => $admin->id,
        ]);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::update
     */
    public function it_should_deny_to_update_task_without_ability()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $task = Task::factory()->create(['user_id' => $user->id, 'status' => TaskStatus::TODO]);

        $response = $this->patchJson('/api/tasks/' . $task->id, $this->getTaskModifyRequest());
        $response->assertForbidden();
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::destroy
     */
    public function it_should_allow_to_destroy_own_tasks()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::REMOVE_OWN_TASKS->value]);
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson('/api/tasks/' . $task->id);
        $response->assertNoContent();
        $this->assertDatabaseCount('tasks', 0);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::destroy
     */
    public function it_should_allow_to_destroy_all_tasks()
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['type' => UserType::ADMIN]);
        Sanctum::actingAs($admin, [Ability::REMOVE_ALL_TASKS->value]);
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson('/api/tasks/' . $task->id);
        $response->assertNoContent();
        $this->assertDatabaseCount('tasks', 0);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\TaskController::destroy
     */
    public function it_should_deny_to_destroy_task_without_ability()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->delete('/api/tasks/' . $task->id);
        $response->assertForbidden();
    }

    private function getTaskModifyRequest(?int $userId = null): array
    {
        $request = [
            'title' => fake()->text(256),
            'description' => fake()->text(),
            'status' => TaskStatus::IN_PROGRESS->value,
        ];

        if ($userId !== null) {
            $request['user_id'] = $userId;
        }

        return $request;
    }
}
