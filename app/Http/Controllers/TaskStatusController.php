<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskStatusRequest;
use App\Repositories\TaskStatusRepository;
use App\Services\TaskStatusCreator;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    public function index(TaskStatusRepository $taskStatusRepository)
    {
        $taskStatuses = $taskStatusRepository->getAll();

        return view('task_statuses.index', compact('taskStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('task_statuses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreTaskStatusRequest $storeTaskStatusRequest,
        TaskStatusCreator $taskStatusCreator
    ) {
        $taskStatusCreator->create($storeTaskStatusRequest->validated());

        return redirect()->route('task_statuses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
