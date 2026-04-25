<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\TaskCreator;
use App\Services\TaskDeleter;
use App\Services\TaskUpdater;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
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

    public function create(TaskRepository $taskRepository): View
    {
        $taskStatuses = $taskRepository->findAllStatuses();
        $users = $taskRepository->findAllUsers();
        $labels = $taskRepository->findAllLabels();

        return view(
            'tasks.create',
            compact('taskStatuses', 'users', 'labels')
        );
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
        TaskRepository $taskRepository
    ): View {
        $taskStatuses = $taskRepository->findAllStatuses();
        $users = $taskRepository->findAllUsers();
        $labels = $taskRepository->findAllLabels();

        return view(
            'tasks.edit',
            compact('task', 'taskStatuses', 'users', 'labels')
        );
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
        $taskDeleter->delete($task, Auth::user());

        flash(__('tasks.flash.deleted'))->success();

        return redirect()->route('tasks.index');
    }
}
