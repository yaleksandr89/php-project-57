<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_tasks_index(): void
    {
        $response = $this->get(route('tasks.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_task_create_page(): void
    {
        $response = $this->get(route('tasks.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_store_task(): void
    {
        $taskStatus = TaskStatus::factory()->create();
        $assignee = User::factory()->create();

        $response = $this->post(route('tasks.store'), [
            'name' => 'New task',
            'description' => 'Task description',
            'status_id' => $taskStatus->id,
            'assigned_to_id' => $assignee->id,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('tasks', [
            'name' => 'New task',
        ]);
    }

    public function test_guest_cannot_access_task_show_page(): void
    {
        $task = Task::factory()->create();

        $response = $this->get(route('tasks.show', $task));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_task_edit_page(): void
    {
        $task = Task::factory()->create();

        $response = $this->get(route('tasks.edit', $task));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_update_task(): void
    {
        $task = Task::factory()->create([
            'name' => 'Old task',
        ]);

        $response = $this->patch(route('tasks.update', $task), [
            'name' => 'Updated task',
            'description' => 'Updated description',
            'status_id' => $task->status_id,
            'assigned_to_id' => $task->assigned_to_id,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Old task',
        ]);
    }

    public function test_guest_cannot_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_authenticated_user_can_view_tasks_index(): void
    {
        $user = User::factory()->create();
        $tasks = Task::factory()->count(2)->create();

        $response = $this->actingAs($user)->get(route('tasks.index'));

        $response->assertOk();
        $response->assertSee($tasks[0]->name);
        $response->assertSee($tasks[1]->name);
    }

    public function test_authenticated_user_can_view_task_show_page(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'name' => 'Important task',
            'description' => 'Important description',
        ]);

        $response = $this->actingAs($user)->get(route('tasks.show', $task));

        $response->assertOk();
        $response->assertSee('Important task');
        $response->assertSee('Important description');
    }

    public function test_authenticated_user_can_store_task(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();
        $assignee = User::factory()->create();

        $response = $this->actingAs($user)->post(route('tasks.store'), [
            'name' => 'New task',
            'description' => 'Task description',
            'status_id' => $taskStatus->id,
            'assigned_to_id' => $assignee->id,
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'name' => 'New task',
            'description' => 'Task description',
            'status_id' => $taskStatus->id,
            'created_by_id' => $user->id,
            'assigned_to_id' => $assignee->id,
        ]);
    }

    public function test_authenticated_user_can_store_task_without_assignee(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->actingAs($user)->post(route('tasks.store'), [
            'name' => 'Task without assignee',
            'description' => 'Task description',
            'status_id' => $taskStatus->id,
            'assigned_to_id' => null,
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'name' => 'Task without assignee',
            'status_id' => $taskStatus->id,
            'created_by_id' => $user->id,
            'assigned_to_id' => null,
        ]);
    }

    public function test_store_task_validation_fails_when_name_is_empty(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('tasks.create'))
            ->post(route('tasks.store'), [
                'name' => '',
                'description' => 'Task description',
                'status_id' => $taskStatus->id,
                'assigned_to_id' => null,
            ]);

        $response->assertRedirect(route('tasks.create'));
        $response->assertSessionHasErrors('name');
    }

    public function test_store_task_validation_fails_when_status_is_empty(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('tasks.create'))
            ->post(route('tasks.store'), [
                'name' => 'New task',
                'description' => 'Task description',
                'status_id' => null,
                'assigned_to_id' => null,
            ]);

        $response->assertRedirect(route('tasks.create'));
        $response->assertSessionHasErrors('status_id');
    }

    public function test_authenticated_user_can_view_task_edit_page(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'name' => 'Editable task',
        ]);

        $response = $this->actingAs($user)->get(route('tasks.edit', $task));

        $response->assertOk();
        $response->assertSee('Editable task');
    }

    public function test_authenticated_user_can_update_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'name' => 'Old task',
        ]);
        $taskStatus = TaskStatus::factory()->create();
        $assignee = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('tasks.update', $task), [
            'name' => 'Updated task',
            'description' => 'Updated description',
            'status_id' => $taskStatus->id,
            'assigned_to_id' => $assignee->id,
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Updated task',
            'description' => 'Updated description',
            'status_id' => $taskStatus->id,
            'assigned_to_id' => $assignee->id,
        ]);
    }

    public function test_update_task_validation_fails_when_name_is_empty(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'name' => 'Stable task',
        ]);

        $response = $this->actingAs($user)
            ->from(route('tasks.edit', $task))
            ->patch(route('tasks.update', $task), [
                'name' => '',
                'description' => 'Updated description',
                'status_id' => $task->status_id,
                'assigned_to_id' => $task->assigned_to_id,
            ]);

        $response->assertRedirect(route('tasks.edit', $task));
        $response->assertSessionHasErrors('name');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Stable task',
        ]);
    }

    public function test_creator_can_delete_task(): void
    {
        $creator = User::factory()->create();
        $task = Task::factory()->create([
            'created_by_id' => $creator->id,
        ]);

        $response = $this->actingAs($creator)->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_not_creator_cannot_delete_task(): void
    {
        $creator = User::factory()->create();
        $anotherUser = User::factory()->create();

        $task = Task::factory()->create([
            'created_by_id' => $creator->id,
        ]);

        $response = $this->actingAs($anotherUser)->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_authenticated_user_can_store_task_with_labels(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();
        $assignee = User::factory()->create();
        $labels = Label::factory()->count(2)->create();

        $response = $this->actingAs($user)->post(route('tasks.store'), [
            'name' => 'Task with labels',
            'description' => 'Task description',
            'status_id' => $taskStatus->id,
            'assigned_to_id' => $assignee->id,
            'labels' => $labels->pluck('id')->all(),
        ]);

        $response->assertRedirect(route('tasks.index'));

        $task = Task::query()
            ->where('name', 'Task with labels')
            ->firstOrFail();

        $this->assertDatabaseHas('label_task', [
            'task_id' => $task->id,
            'label_id' => $labels[0]->id,
        ]);

        $this->assertDatabaseHas('label_task', [
            'task_id' => $task->id,
            'label_id' => $labels[1]->id,
        ]);
    }

    public function test_authenticated_user_can_update_task_labels(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $oldLabel = Label::factory()->create();
        $newLabel = Label::factory()->create();

        $task->labels()->attach($oldLabel);

        $response = $this->actingAs($user)->patch(route('tasks.update', $task), [
            'name' => 'Updated task labels',
            'description' => 'Updated description',
            'status_id' => $task->status_id,
            'assigned_to_id' => $task->assigned_to_id,
            'labels' => [$newLabel->id],
        ]);

        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('label_task', [
            'task_id' => $task->id,
            'label_id' => $oldLabel->id,
        ]);

        $this->assertDatabaseHas('label_task', [
            'task_id' => $task->id,
            'label_id' => $newLabel->id,
        ]);
    }

    public function test_authenticated_user_can_remove_all_task_labels(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $label = Label::factory()->create();

        $task->labels()->attach($label);

        $response = $this->actingAs($user)->patch(route('tasks.update', $task), [
            'name' => 'Task without labels',
            'description' => 'Updated description',
            'status_id' => $task->status_id,
            'assigned_to_id' => $task->assigned_to_id,
            'labels' => [],
        ]);

        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('label_task', [
            'task_id' => $task->id,
            'label_id' => $label->id,
        ]);
    }

    public function test_store_task_validation_fails_when_label_does_not_exist(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('tasks.create'))
            ->post(route('tasks.store'), [
                'name' => 'Task with invalid label',
                'description' => 'Task description',
                'status_id' => $taskStatus->id,
                'assigned_to_id' => null,
                'labels' => [999999],
            ]);

        $response->assertRedirect(route('tasks.create'));
        $response->assertSessionHasErrors('labels.0');
    }

    public function test_update_task_validation_fails_when_label_does_not_exist(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('tasks.edit', $task))
            ->patch(route('tasks.update', $task), [
                'name' => 'Task with invalid label',
                'description' => 'Task description',
                'status_id' => $task->status_id,
                'assigned_to_id' => $task->assigned_to_id,
                'labels' => [999999],
            ]);

        $response->assertRedirect(route('tasks.edit', $task));
        $response->assertSessionHasErrors('labels.0');
    }

    public function test_authenticated_user_can_view_task_labels_on_show_page(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()->create([
            'name' => 'Task with visible labels',
        ]);

        $labels = Label::factory()->count(2)->create();

        $task->labels()->attach($labels);

        $response = $this->actingAs($user)->get(route('tasks.show', $task));

        $response->assertOk();
        $response->assertSee('Task with visible labels');
        $response->assertSee($labels[0]->name);
        $response->assertSee($labels[1]->name);
    }
}
