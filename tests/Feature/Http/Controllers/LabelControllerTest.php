<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabelControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_labels_index(): void
    {
        $response = $this->get(route('labels.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_labels_create_page(): void
    {
        $response = $this->get(route('labels.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_store_label(): void
    {
        $response = $this->post(route('labels.store'), [
            'name' => 'bug',
            'description' => 'Bug description',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('labels', [
            'name' => 'bug',
        ]);
    }

    public function test_guest_cannot_access_labels_edit_page(): void
    {
        /** @var Label $label */
        $label = Label::factory()->create();

        $response = $this->get(route('labels.edit', $label));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_update_label(): void
    {
        /** @var Label $label */
        $label = Label::factory()->create([
            'name' => 'bug',
        ]);

        $response = $this->patch(route('labels.update', $label), [
            'name' => 'feature',
            'description' => 'Feature description',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('labels', [
            'id' => $label->id,
            'name' => 'bug',
        ]);
    }

    public function test_guest_cannot_delete_label(): void
    {
        /** @var Label $label */
        $label = Label::factory()->create();

        $response = $this->delete(route('labels.destroy', $label));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('labels', [
            'id' => $label->id,
        ]);
    }

    public function test_authenticated_user_can_view_labels_index(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Label $label */
        $labels = Label::factory()
            ->count(2)
            ->create();

        $response = $this->actingAs($user)->get(route('labels.index'));

        $response->assertOk();
        $response->assertSee($labels[0]->name);
        $response->assertSee($labels[1]->name);
    }

    public function test_authenticated_user_can_store_label(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('labels.store'), [
            'name' => 'bug',
            'description' => 'Bug description',
        ]);

        $response->assertRedirect(route('labels.index'));

        $this->assertDatabaseHas('labels', [
            'name' => 'bug',
            'description' => 'Bug description',
        ]);
    }

    public function test_store_label_validation_fails_when_name_is_empty(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('labels.create'))
            ->post(route('labels.store'), [
                'name' => '',
                'description' => 'Bug description',
            ]);

        $response->assertRedirect(route('labels.create'));
        $response->assertSessionHasErrors('name');
    }

    public function test_authenticated_user_can_view_label_edit_page(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Label $label */
        $label = Label::factory()->create([
            'name' => 'bug',
            'description' => 'Bug description',
        ]);

        $response = $this->actingAs($user)->get(route('labels.edit', $label));

        $response->assertOk();
        $response->assertSee('bug');
        $response->assertSee('Bug description');
    }

    public function test_authenticated_user_can_update_label(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Label $label */
        $label = Label::factory()->create([
            'name' => 'bug',
            'description' => 'Bug description',
        ]);

        $response = $this->actingAs($user)->patch(route('labels.update', $label), [
            'name' => 'feature',
            'description' => 'Feature description',
        ]);

        $response->assertRedirect(route('labels.index'));

        $this->assertDatabaseHas('labels', [
            'id' => $label->id,
            'name' => 'feature',
            'description' => 'Feature description',
        ]);
    }

    public function test_update_label_validation_fails_when_name_is_empty(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Label $label */
        $label = Label::factory()->create([
            'name' => 'bug',
            'description' => 'Bug description',
        ]);

        $response = $this->actingAs($user)
            ->from(route('labels.edit', $label))
            ->patch(route('labels.update', $label), [
                'name' => '',
                'description' => 'Bug description',
            ]);

        $response->assertRedirect(route('labels.edit', $label));
        $response->assertSessionHasErrors('name');

        $this->assertDatabaseHas('labels', [
            'id' => $label->id,
            'name' => 'bug',
        ]);
    }

    public function test_authenticated_user_can_delete_label(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Label $label */
        $label = Label::factory()->create();

        $response = $this->actingAs($user)->delete(route('labels.destroy', $label));

        $response->assertRedirect(route('labels.index'));

        $this->assertDatabaseMissing('labels', [
            'id' => $label->id,
        ]);
    }

    public function test_authenticated_user_cannot_delete_label_used_in_task(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Label $label */
        $label = Label::factory()->create();

        $task = Task::factory()->create([
            'created_by_id' => $user->id,
        ]);

        $task->labels()->attach($label);

        $response = $this->actingAs($user)->delete(route('labels.destroy', $label));

        $response->assertRedirect(route('labels.index'));
        $response->assertSessionHas('flash_notification');

        $this->assertDatabaseHas('labels', [
            'id' => $label->id,
        ]);

        $this->assertDatabaseHas('label_task', [
            'label_id' => $label->id,
            'task_id' => $task->id,
        ]);
    }
}
