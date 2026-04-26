@php
    $selectedLabelIds = collect(old(
        'labels',
        isset($task) ? $task->labels->pluck('id')->all() : []
    ))
        ->map(fn ($labelId) => (int) $labelId)
        ->all();

    $statusOptions = $taskStatuses->pluck('name', 'id')->prepend('', '')->all();
    $userOptions = $users->pluck('name', 'id')->prepend(__('tasks.empty_assignee'), '')->all();
    $labelOptions = $labels->pluck('name', 'id')->all();
@endphp

<div class="mb-3">
    {!! html()->label(__('tasks.fields.name'), 'name')->class('form-label') !!}

    {!! html()
        ->text('name')
        ->value(old('name', $task->name ?? ''))
        ->id('name')
        ->class('form-control' . ($errors->has('name') ? ' is-invalid' : '')) !!}

    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    {!! html()->label(__('tasks.fields.description'), 'description')->class('form-label') !!}

    {!! html()
        ->textarea('description')
        ->value(old('description', $task->description ?? ''))
        ->id('description')
        ->class('form-control' . ($errors->has('description') ? ' is-invalid' : ''))
        ->rows(6) !!}

    @error('description')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    {!! html()->label(__('tasks.fields.status'), 'status_id')->class('form-label') !!}

    {!! html()
        ->select('status_id', $statusOptions, old('status_id', $task->status_id ?? ''))
        ->id('status_id')
        ->class('form-control' . ($errors->has('status_id') ? ' is-invalid' : '')) !!}

    @error('status_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    {!! html()->label(__('tasks.fields.assignee'), 'assigned_to_id')->class('form-label') !!}

    {!! html()
        ->select('assigned_to_id', $userOptions, old('assigned_to_id', $task->assigned_to_id ?? ''))
        ->id('assigned_to_id')
        ->class('form-control' . ($errors->has('assigned_to_id') ? ' is-invalid' : '')) !!}

    @error('assigned_to_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    {!! html()->label(__('tasks.fields.labels'), 'labels')->class('form-label') !!}

    {!! html()
        ->select('labels[]', $labelOptions, $selectedLabelIds)
        ->id('labels')
        ->class('form-control' . ($errors->has('labels') || $errors->has('labels.*') ? ' is-invalid' : ''))
        ->multiple()
        ->attribute('data-placeholder', __('tasks.fields.labels')) !!}

    @error('labels')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @error('labels.*')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{!! html()->submit($buttonText)->class('btn btn-primary') !!}
