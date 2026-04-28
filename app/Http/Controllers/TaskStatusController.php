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

class TaskStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
        $this->authorizeResource(TaskStatus::class, 'task_status');
    }

    public function index(TaskStatusRepository $taskStatusRepository): View
    {
        $taskStatuses = $taskStatusRepository->getPaginated();

        return view('task_statuses.index', compact('taskStatuses'));
    }

    public function create(): View
    {
        return view('task_statuses.create');
    }

    public function store(
        StoreTaskStatusRequest $storeTaskStatusRequest,
        TaskStatusCreator $taskStatusCreator
    ): RedirectResponse {
        $taskStatusCreator->create($storeTaskStatusRequest->validated());

        flash(__('task_statuses.flash.created'))->success();
        return redirect()->route('task_statuses.index');
    }

    public function edit(TaskStatus $taskStatus): View
    {
        return view('task_statuses.edit', compact('taskStatus'));
    }

    public function update(
        UpdateTaskStatusRequest $updateTaskStatusRequest,
        TaskStatus $taskStatus,
        TaskStatusUpdater $taskStatusUpdater
    ): RedirectResponse {
        $taskStatusUpdater->update($taskStatus, $updateTaskStatusRequest->validated());

        flash(__('task_statuses.flash.updated'))->success();
        return redirect()->route('task_statuses.index');
    }

    public function destroy(
        TaskStatus $taskStatus,
        TaskStatusDeleter $taskStatusDeleter
    ): RedirectResponse {
        try {
            $taskStatusDeleter->delete($taskStatus);

            flash(__('task_statuses.flash.deleted'))->success();
        } catch (TaskStatusIsUsedException) {
            flash(__('task_statuses.flash.delete_failed'))->error();
        }

        return redirect()->route('task_statuses.index');
    }
}
