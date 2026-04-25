@php
    $selectedLabelIds = collect(old(
        'labels',
        isset($task) ? $task->labels->pluck('id')->all() : []
    ))
        ->map(fn ($labelId) => (int) $labelId)
        ->all();
@endphp

<div class="mb-3">
    <label for="name" class="form-label">
        {{ __('tasks.fields.name') }}
    </label>

    <input
        id="name"
        name="name"
        type="text"
        value="{{ old('name', $task->name ?? '') }}"
        class="form-control @error('name') is-invalid @enderror"
    >

    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">
        {{ __('tasks.fields.description') }}
    </label>

    <textarea
        id="description"
        name="description"
        class="form-control @error('description') is-invalid @enderror"
        rows="6"
    >{{ old('description', $task->description ?? '') }}</textarea>

    @error('description')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="status_id" class="form-label">
        {{ __('tasks.fields.status') }}
    </label>

    <select
        id="status_id"
        name="status_id"
        class="form-control @error('status_id') is-invalid @enderror"
    >
        @foreach ($taskStatuses as $taskStatus)
            <option
                value="{{ $taskStatus->id }}"
                @selected((int) old('status_id', $task->status_id ?? 0) === $taskStatus->id)
            >
                {{ $taskStatus->name }}
            </option>
        @endforeach
    </select>

    @error('status_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="assigned_to_id" class="form-label">
        {{ __('tasks.fields.assignee') }}
    </label>

    <select
        id="assigned_to_id"
        name="assigned_to_id"
        class="form-control @error('assigned_to_id') is-invalid @enderror"
    >
        <option value="">{{ __('tasks.empty_assignee') }}</option>

        @foreach ($users as $user)
            <option
                value="{{ $user->id }}"
                @selected((int) old('assigned_to_id', $task->assigned_to_id ?? 0) === $user->id)
            >
                {{ $user->name }}
            </option>
        @endforeach
    </select>

    @error('assigned_to_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="labels" class="form-label">
        {{ __('tasks.fields.labels') }}
    </label>

    <select
        id="labels"
        name="labels[]"
        class="form-control @error('labels') is-invalid @enderror @error('labels.*') is-invalid @enderror"
        multiple
        data-placeholder="{{ __('tasks.fields.labels') }}"
    >
        @foreach ($labels as $label)
            <option
                value="{{ $label->id }}"
                @selected(in_array($label->id, $selectedLabelIds, true))
            >
                {{ $label->name }}
            </option>
        @endforeach
    </select>

    @error('labels')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @error('labels.*')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-primary">
    {{ $buttonText }}
</button>
