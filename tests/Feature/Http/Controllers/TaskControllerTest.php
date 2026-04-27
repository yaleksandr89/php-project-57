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

    public function testGuestCanAccessTasksIndex(): void
    {
        $response = $this->get(route('tasks.index'));

        $response->assertOk();
    }

    public function testGuestCannotAccessTaskCreatePage(): void
    {
        $response = $this->get(route('tasks.create'));

        $response->assertRedirect(route('login'));
    }

    public function testGuestCannotStoreTask(): void
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

    public function testGuestCannotAccessTaskShowPage(): void
    {
        $task = Task::factory()->create();

        $response = $this->get(route('tasks.show', $task));

        $response->assertRedirect(route('login'));
    }

    public function testGuestCannotAccessTaskEditPage(): void
    {
        $task = Task::factory()->create();

        $response = $this->get(route('tasks.edit', $task));

        $response->assertRedirect(route('login'));
    }

    public function testGuestCannotUpdateTask(): void
    {
        $task = Task::factory()->create([
            'name' => 'Old task',
        ]);

        $response = $this->patch(route('tasks.update', $task), [
            'name' => 'Updated task',
            'description' => 'Updated description',
            'status_id' => $task->getAttribute('status_id'),
            'assigned_to_id' => $task->getAttribute('assigned_to_id'),
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Old task',
        ]);
    }

    public function testGuestCannotDeleteTask(): void
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
        ]);
    }

    public function testAuthenticatedUserCanViewTasksIndex(): void
    {
        $user = User::factory()->create();
        $tasks = Task::factory()->count(2)->create();

        $response = $this->actingAs($user)->get(route('tasks.index'));

        $response->assertOk();
        $response->assertSee($tasks[0]->name);
        $response->assertSee($tasks[1]->name);
    }

    public function testAuthenticatedUserCanViewTaskShowPage(): void
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

    public function testAuthenticatedUserCanStoreTask(): void
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

    public function testAuthenticatedUserCanStoreTaskWithoutAssignee(): void
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

    public function testStoreTaskValidationFailsWhenNameIsEmpty(): void
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

    public function testStoreTaskValidationFailsWhenStatusIsEmpty(): void
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

    public function testAuthenticatedUserCanViewTaskEditPage(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'name' => 'Editable task',
        ]);

        $response = $this->actingAs($user)->get(route('tasks.edit', $task));

        $response->assertOk();
        $response->assertSee('Editable task');
    }

    public function testAuthenticatedUserCanUpdateTask(): void
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

    public function testUpdateTaskValidationFailsWhenNameIsEmpty(): void
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
                'status_id' => $task->getAttribute('status_id'),
                'assigned_to_id' => $task->getAttribute('assigned_to_id'),
            ]);

        $response->assertRedirect(route('tasks.edit', $task));
        $response->assertSessionHasErrors('name');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Stable task',
        ]);
    }

    public function testCreatorCanDeleteTask(): void
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

    public function testNotCreatorCannotDeleteTask(): void
    {
        $creator = User::factory()->create();
        $anotherUser = User::factory()->create();

        $task = Task::factory()->create([
            'created_by_id' => $creator->id,
        ]);

        $response = $this->actingAs($anotherUser)->delete(route('tasks.destroy', $task));

        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
        ]);
    }

    public function testAuthenticatedUserCanStoreTaskWithLabels(): void
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

    public function testAuthenticatedUserCanUpdateTaskLabels(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $oldLabel = Label::factory()->create();
        $newLabel = Label::factory()->create();

        $task->labels()->attach($oldLabel);

        $response = $this->actingAs($user)->patch(route('tasks.update', $task), [
            'name' => 'Updated task labels',
            'description' => 'Updated description',
            'status_id' => $task->getAttribute('status_id'),
            'assigned_to_id' => $task->getAttribute('assigned_to_id'),
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

    public function testAuthenticatedUserCanRemoveAllTaskLabels(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $label = Label::factory()->create();

        $task->labels()->attach($label);

        $response = $this->actingAs($user)->patch(route('tasks.update', $task), [
            'name' => 'Task without labels',
            'description' => 'Updated description',
            'status_id' => $task->getAttribute('status_id'),
            'assigned_to_id' => $task->getAttribute('assigned_to_id'),
            'labels' => [],
        ]);

        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('label_task', [
            'task_id' => $task->id,
            'label_id' => $label->id,
        ]);
    }

    public function testStoreTaskValidationFailsWhenLabelDoesNotExist(): void
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

    public function testUpdateTaskValidationFailsWhenLabelDoesNotExist(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('tasks.edit', $task))
            ->patch(route('tasks.update', $task), [
                'name' => 'Task with invalid label',
                'description' => 'Task description',
                'status_id' => $task->getAttribute('status_id'),
                'assigned_to_id' => $task->getAttribute('assigned_to_id'),
                'labels' => [999999],
            ]);

        $response->assertRedirect(route('tasks.edit', $task));
        $response->assertSessionHasErrors('labels.0');
    }

    public function testAuthenticatedUserCanViewTaskLabelsOnShowPage(): void
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

    public function testAuthenticatedUserCanFilterTasksByStatus(): void
    {
        $user = User::factory()->create();
        $taskStatus = TaskStatus::factory()->create();
        $anotherTaskStatus = TaskStatus::factory()->create();

        $visibleTask = Task::factory()->create([
            'name' => 'Visible status task',
            'status_id' => $taskStatus->id,
        ]);

        $hiddenTask = Task::factory()->create([
            'name' => 'Hidden status task',
            'status_id' => $anotherTaskStatus->id,
        ]);

        $response = $this->actingAs($user)->get(route('tasks.index', [
            'filter' => [
                'status_id' => $taskStatus->id,
            ],
        ]));

        $response->assertOk();
        $response->assertSee($visibleTask->name);
        $response->assertDontSee($hiddenTask->name);
    }

    public function testAuthenticatedUserCanFilterTasksByCreator(): void
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $anotherCreator = User::factory()->create();

        $visibleTask = Task::factory()->create([
            'name' => 'Visible creator task',
            'created_by_id' => $creator->id,
        ]);

        $hiddenTask = Task::factory()->create([
            'name' => 'Hidden creator task',
            'created_by_id' => $anotherCreator->id,
        ]);

        $response = $this->actingAs($user)->get(route('tasks.index', [
            'filter' => [
                'created_by_id' => $creator->id,
            ],
        ]));

        $response->assertOk();
        $response->assertSee($visibleTask->name);
        $response->assertDontSee($hiddenTask->name);
    }

    public function testAuthenticatedUserCanFilterTasksByAssignee(): void
    {
        $user = User::factory()->create();
        $assignee = User::factory()->create();
        $anotherAssignee = User::factory()->create();

        $visibleTask = Task::factory()->create([
            'name' => 'Visible assignee task',
            'assigned_to_id' => $assignee->id,
        ]);

        $hiddenTask = Task::factory()->create([
            'name' => 'Hidden assignee task',
            'assigned_to_id' => $anotherAssignee->id,
        ]);

        $response = $this->actingAs($user)->get(route('tasks.index', [
            'filter' => [
                'assigned_to_id' => $assignee->id,
            ],
        ]));

        $response->assertOk();
        $response->assertSee($visibleTask->name);
        $response->assertDontSee($hiddenTask->name);
    }

    public function testAuthenticatedUserCanFilterTasksByLabel(): void
    {
        $user = User::factory()->create();
        $label = Label::factory()->create();
        $anotherLabel = Label::factory()->create();

        $visibleTask = Task::factory()->create([
            'name' => 'Visible label task',
        ]);

        $hiddenTask = Task::factory()->create([
            'name' => 'Hidden label task',
        ]);

        $visibleTask->labels()->attach($label);
        $hiddenTask->labels()->attach($anotherLabel);

        $response = $this->actingAs($user)->get(route('tasks.index', [
            'filter' => [
                'label_id' => $label->id,
            ],
        ]));

        $response->assertOk();
        $response->assertSee($visibleTask->name);
        $response->assertDontSee($hiddenTask->name);
    }
}
