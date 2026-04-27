<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\TaskCreator;
use App\Services\TaskDeleter;
use App\Services\TaskFormDataBuilder;
use App\Services\TaskUpdater;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller implements HasMiddleware
{
    public function index(TaskRepository $taskRepository): View
    {
        $tasks = $taskRepository->getPaginated();
        $taskStatuses = $taskRepository->findAllStatuses();
        $users = $taskRepository->findAllUsers();
        $labels = $taskRepository->findAllLabels();

        return view(
            'tasks.index',
            compact('tasks', 'taskStatuses', 'users', 'labels')
        );
    }

    public function create(TaskFormDataBuilder $taskFormDataBuilder): View
    {
        Gate::authorize('create', Task::class);

        return view('tasks.create', $taskFormDataBuilder->build());
    }

    public function store(
        StoreTaskRequest $request,
        TaskCreator $taskCreator
    ): RedirectResponse {
        Gate::authorize('create', Task::class);

        $taskCreator->create($request->validated(), Auth::user());

        flash(__('tasks.flash.created'))->success();

        return redirect()->route('tasks.index');
    }

    public function show(Task $task): View
    {
        Gate::authorize('view', $task);

        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    public function edit(
        Task $task,
        TaskFormDataBuilder $taskFormDataBuilder
    ): View {
        Gate::authorize('update', $task);

        return view('tasks.edit', $taskFormDataBuilder->build($task));
    }

    public function update(
        UpdateTaskRequest $request,
        Task $task,
        TaskUpdater $taskUpdater
    ): RedirectResponse {
        Gate::authorize('update', $task);

        $taskUpdater->update($task, $request->validated());

        flash(__('tasks.flash.updated'))->success();

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task, TaskDeleter $taskDeleter): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $taskDeleter->delete($task);

        flash(__('tasks.flash.deleted'))->success();

        return redirect()->route('tasks.index');
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index']),
        ];
    }
}
