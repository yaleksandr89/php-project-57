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

        return view('tasks.index', compact('tasks'));
    }

    public function create(): View
    {
        return view('tasks.create');
    }

    public function store(
        StoreTaskRequest $request,
        TaskCreator $taskCreator
    ): RedirectResponse {
        $taskCreator->create($request->validated(), Auth::user());

        return redirect()->route('tasks.index');
    }

    public function show(Task $task): View
    {
        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    public function edit(Task $task): View
    {
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    public function update(
        UpdateTaskRequest $request,
        Task $task,
        TaskUpdater $taskUpdater
    ): RedirectResponse {
        $taskUpdater->update($task, $request->validated());

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task, TaskDeleter $taskDeleter): RedirectResponse
    {
        $taskDeleter->delete($task, Auth::user());

        return redirect()->route('tasks.index');
    }
}
