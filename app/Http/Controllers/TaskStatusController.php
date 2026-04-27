<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\TaskStatusIsUsedException;
use App\Http\Requests\StoreTaskStatusRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\TaskStatus;
use App\Repositories\TaskStatusRepository;
use App\Services\TaskStatusCreator;
use App\Services\TaskStatusDeleter;
use App\Services\TaskStatusUpdater;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class TaskStatusController extends Controller implements HasMiddleware
{
    public function index(TaskStatusRepository $taskStatusRepository): View
    {
        $taskStatuses = $taskStatusRepository->getPaginated();

        return view('task_statuses.index', compact('taskStatuses'));
    }

    public function create(): View
    {
        Gate::authorize('create', TaskStatus::class);

        return view('task_statuses.create');
    }

    public function store(
        StoreTaskStatusRequest $storeTaskStatusRequest,
        TaskStatusCreator $taskStatusCreator
    ): RedirectResponse {
        Gate::authorize('create', TaskStatus::class);

        $taskStatusCreator->create($storeTaskStatusRequest->validated());

        flash(__('task_statuses.flash.created'))->success();

        return redirect()->route('task_statuses.index');
    }

    public function edit(TaskStatus $taskStatus): View
    {
        Gate::authorize('update', $taskStatus);

        return view('task_statuses.edit', compact('taskStatus'));
    }

    public function update(
        UpdateTaskStatusRequest $updateTaskStatusRequest,
        TaskStatus $taskStatus,
        TaskStatusUpdater $taskStatusUpdater
    ): RedirectResponse {
        Gate::authorize('update', $taskStatus);

        $taskStatusUpdater->update($taskStatus, $updateTaskStatusRequest->validated());

        flash(__('task_statuses.flash.updated'))->success();

        return redirect()->route('task_statuses.index');
    }

    public function destroy(
        TaskStatus $taskStatus,
        TaskStatusDeleter $taskStatusDeleter
    ): RedirectResponse {
        try {
            Gate::authorize('delete', $taskStatus);

            $taskStatusDeleter->delete($taskStatus);

            flash(__('task_statuses.flash.deleted'))->success();
        } catch (TaskStatusIsUsedException) {
            flash(__('task_statuses.flash.delete_failed'))->error();
        }

        return redirect()->route('task_statuses.index');
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index']),
        ];
    }
}
