<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStatusControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCanAccessTaskStatusesIndex(): void
    {
        $response = $this->get(route('task_statuses.index'));

        $response->assertOk();
    }

    public function testGuestCannotAccessTaskStatusesCreatePage(): void
    {
        $response = $this->get(route('task_statuses.create'));

        $response->assertForbidden();
    }

    public function testGuestCannotStoreTaskStatus(): void
    {
        $response = $this->post(route('task_statuses.store'), [
            'name' => 'New status',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('task_statuses', ['name' => 'New status']);
    }

    public function testGuestCannotAccessTaskStatusesEditPage(): void
    {
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->get(route('task_statuses.edit', $taskStatus));

        $response->assertForbidden();
    }

    public function testGuestCannotUpdateTaskStatus(): void
    {
        $taskStatus = TaskStatus::factory()->create([
            'name' => 'Old status',
        ]);

        $response = $this->patch(route('task_statuses.update', $taskStatus), [
            'name' => 'Updated status',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('task_statuses', [
            'id' => $taskStatus->id,
            'name' => 'Old status',
        ]);
    }

    public function testGuestCannotDeleteTaskStatus(): void
    {
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->delete(route('task_statuses.destroy', $taskStatus));

        $response->assertForbidden();
        $this->assertDatabaseHas('task_statuses', [
            'id' => $taskStatus->id,
        ]);
    }

    public function testAuthenticatedUserCanViewTaskStatusesIndex(): void
    {
        $user = User::factory()->create();
        $taskStatuses = TaskStatus::factory()->count(2)->create();

        $response = $this->actingAs($user)->get(route('task_statuses.index'));

        $response->assertOk();
        $response->assertSee($taskStatuses[0]->name);
        $response->assertSee($taskStatuses[1]->name);
    }

    public function testAuthenticatedUserCanStoreTaskStatus(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('task_statuses.store'), [
            'name' => 'New status',
        ]);

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', [
            'name' => 'New status',
        ]);
    }

    public function testStoreTaskStatusValidationFailsWhenNameIsEmpty(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('task_statuses.create'))
            ->post(route('task_statuses.store'), [
                'name' => '',
            ]);

        $response->assertRedirect(route('task_statuses.create'));
        $response->assertSessionHasErrors('name');
    }

    public function testAuthenticatedUserCanViewTaskStatusEditPage(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create([
            'name' => 'In progress',
        ]);

        $response = $this->actingAs($user)->get(route('task_statuses.edit', $taskStatus));

        $response->assertOk();
        $response->assertSee('In progress');
    }

    public function testAuthenticatedUserCanUpdateTaskStatus(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create([
            'name' => 'Old status',
        ]);

        $response = $this->actingAs($user)->patch(route('task_statuses.update', $taskStatus), [
            'name' => 'Updated status',
        ]);

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', [
            'id' => $taskStatus->id,
            'name' => 'Updated status',
        ]);
    }

    public function testUpdateTaskStatusValidationFailsWhenNameIsEmpty(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create([
            'name' => 'Stable status',
        ]);

        $response = $this->actingAs($user)
            ->from(route('task_statuses.edit', $taskStatus))
            ->patch(route('task_statuses.update', $taskStatus), [
                'name' => '',
            ]);

        $response->assertRedirect(route('task_statuses.edit', $taskStatus));
        $response->assertSessionHasErrors('name');

        $this->assertDatabaseHas('task_statuses', [
            'id' => $taskStatus->id,
            'name' => 'Stable status',
        ]);
    }

    public function testAuthenticatedUserCanDeleteTaskStatus(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->actingAs($user)->delete(route('task_statuses.destroy', $taskStatus));

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseMissing('task_statuses', [
            'id' => $taskStatus->id,
        ]);
    }

    public function testAuthenticatedUserCannotDeleteTaskStatusUsedInTask(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();

        Task::factory()->create([
            'status_id' => $taskStatus->id,
            'created_by_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('task_statuses.destroy', $taskStatus));

        $response->assertRedirect(route('task_statuses.index'));
        $response->assertSessionHas('flash_notification');

        $this->assertDatabaseHas('task_statuses', [
            'id' => $taskStatus->id,
        ]);
    }
}
