<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskStatusRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\TaskStatus;
use App\Repositories\TaskStatusRepository;
use App\Services\TaskStatusCreator;
use App\Services\TaskStatusDeleter;
use App\Services\TaskStatusUpdater;
use Illuminate\Http\RedirectResponse;

class TaskStatusController extends Controller
{
    public function index(TaskStatusRepository $taskStatusRepository)
    {
        $taskStatuses = $taskStatusRepository->getAll();

        return view('task_statuses.index', compact('taskStatuses'));
    }

    public function create()
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

    public function edit(TaskStatus $taskStatus)
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
        $taskStatusDeleter->delete($taskStatus);

        flash(__('task_statuses.flash.deleted'))->success();

        return redirect()->route('task_statuses.index');
    }
}
