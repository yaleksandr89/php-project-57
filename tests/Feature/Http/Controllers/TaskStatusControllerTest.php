<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStatusControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_task_statuses_index(): void
    {
        $response = $this->get(route('task_statuses.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_task_statuses_create_page(): void
    {
        $response = $this->get(route('task_statuses.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_store_task_status(): void
    {
        $response = $this->post(route('task_statuses.store'), [
            'name' => 'New status',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('task_statuses', ['name' => 'New status']);
    }

    public function test_guest_cannot_access_task_statuses_edit_page(): void
    {
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->get(route('task_statuses.edit', $taskStatus));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_update_task_status(): void
    {
        $taskStatus = TaskStatus::factory()->create([
            'name' => 'Old status',
        ]);

        $response = $this->patch(route('task_statuses.update', $taskStatus), [
            'name' => 'Updated status',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('task_statuses', [
            'id' => $taskStatus->id,
            'name' => 'Old status',
        ]);
    }

    public function test_guest_cannot_delete_task_status(): void
    {
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->delete(route('task_statuses.destroy', $taskStatus));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('task_statuses', [
            'id' => $taskStatus->id,
        ]);
    }

    public function test_authenticated_user_can_view_task_statuses_index(): void
    {
        $user = User::factory()->create();
        $taskStatuses = TaskStatus::factory()->count(2)->create();

        $response = $this->actingAs($user)->get(route('task_statuses.index'));

        $response->assertOk();
        $response->assertSee($taskStatuses[0]->name);
        $response->assertSee($taskStatuses[1]->name);
    }

    public function test_authenticated_user_can_store_task_status(): void
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

    public function test_store_task_status_validation_fails_when_name_is_empty(): void
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

    public function test_authenticated_user_can_view_task_status_edit_page(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create([
            'name' => 'In progress',
        ]);

        $response = $this->actingAs($user)->get(route('task_statuses.edit', $taskStatus));

        $response->assertOk();
        $response->assertSee('In progress');
    }

    public function test_authenticated_user_can_update_task_status(): void
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

    public function test_update_task_status_validation_fails_when_name_is_empty(): void
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

    public function test_authenticated_user_can_delete_task_status(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var TaskStatus $taskStatus */
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->actingAs($user)->delete(route('task_statuses.destroy', $taskStatus));

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseMissing('task_statuses', [
            'id' => $taskStatus->id,
        ]);
    }
}
