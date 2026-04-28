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
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
        $this->authorizeResource(Task::class, 'task', [
            'except' => ['destroy'], // Для реализации кастомного поведения (редирект на главнуюб + сообщение)
        ]);
    }

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
        return view('tasks.create', $taskFormDataBuilder->build());
    }

    public function store(
        StoreTaskRequest $request,
        TaskCreator $taskCreator
    ): RedirectResponse {
        $taskCreator->create($request->validated(), Auth::user());

        flash(__('tasks.flash.created'))->success();
        return redirect()->route('tasks.index');
    }

    public function show(Task $task): View
    {
        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    public function edit(
        Task $task,
        TaskFormDataBuilder $taskFormDataBuilder
    ): View {
        return view('tasks.edit', $taskFormDataBuilder->build($task));
    }

    public function update(
        UpdateTaskRequest $request,
        Task $task,
        TaskUpdater $taskUpdater
    ): RedirectResponse {
        $taskUpdater->update($task, $request->validated());

        flash(__('tasks.flash.updated'))->success();
        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task, TaskDeleter $taskDeleter): RedirectResponse
    {
        try {
            $this->authorize('delete', $task);
            $taskDeleter->delete($task);

            flash(__('tasks.flash.deleted'))->success();
        } catch (AuthorizationException) {
            flash(__('tasks.flash.delete_failed'))->error();
        }

        return redirect()->route('tasks.index');
    }
}
