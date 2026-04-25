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

    public function testGuestCanAccessLabelsIndex(): void
    {
        $response = $this->get(route('labels.index'));

        $response->assertOk();
    }

    public function testGuestCannotAccessLabelsCreatePage(): void
    {
        $response = $this->get(route('labels.create'));

        $response->assertRedirect(route('login'));
    }

    public function testGuestCannotStoreLabel(): void
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

    public function testGuestCannotAccessLabelsEditPage(): void
    {
        /** @var Label $label */
        $label = Label::factory()->create();

        $response = $this->get(route('labels.edit', $label));

        $response->assertRedirect(route('login'));
    }

    public function testGuestCannotUpdateLabel(): void
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

    public function testGuestCannotDeleteLabel(): void
    {
        /** @var Label $label */
        $label = Label::factory()->create();

        $response = $this->delete(route('labels.destroy', $label));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('labels', [
            'id' => $label->id,
        ]);
    }

    public function testAuthenticatedUserCanViewLabelsIndex(): void
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

    public function testAuthenticatedUserCanStoreLabel(): void
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

    public function testStoreLabelValidationFailsWhenNameIsEmpty(): void
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

    public function testAuthenticatedUserCanViewLabelEditPage(): void
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

    public function testAuthenticatedUserCanUpdateLabel(): void
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

    public function testUpdateLabelValidationFailsWhenNameIsEmpty(): void
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

    public function testAuthenticatedUserCanDeleteLabel(): void
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

    public function testAuthenticatedUserCannotDeleteLabelUsedInTask(): void
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
