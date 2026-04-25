<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('tasks.title') }}
        </h2>
    </x-slot>

    <div class="py-6 pb-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <form method="GET" action="{{ route('tasks.index') }}">
                    <h3 class="h5 mb-3">{{ __('tasks.filters.title') }}</h3>

                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="filter_status_id" class="form-label">
                                {{ __('tasks.filters.status') }}
                            </label>
                            <select id="filter_status_id" name="filter[status_id]" class="form-select">
                                <option value="">{{ __('tasks.filters.empty') }}</option>
                                @foreach ($taskStatuses as $taskStatus)
                                    <option
                                        value="{{ $taskStatus->id }}"
                                        @selected((string) request('filter.status_id') === (string) $taskStatus->id)
                                    >
                                        {{ $taskStatus->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filter_created_by_id" class="form-label">
                                {{ __('tasks.filters.creator') }}
                            </label>
                            <select id="filter_created_by_id" name="filter[created_by_id]" class="form-select">
                                <option value="">{{ __('tasks.filters.empty') }}</option>
                                @foreach ($users as $user)
                                    <option
                                        value="{{ $user->id }}"
                                        @selected((string) request('filter.created_by_id') === (string) $user->id)
                                    >
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filter_assigned_to_id" class="form-label">
                                {{ __('tasks.filters.assignee') }}
                            </label>
                            <select id="filter_assigned_to_id" name="filter[assigned_to_id]" class="form-select">
                                <option value="">{{ __('tasks.filters.empty') }}</option>
                                @foreach ($users as $user)
                                    <option
                                        value="{{ $user->id }}"
                                        @selected((string) request('filter.assigned_to_id') === (string) $user->id)
                                    >
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filter_label_id" class="form-label">
                                {{ __('tasks.filters.label') }}
                            </label>
                            <select id="filter_label_id" name="filter[label_id]" class="form-select">
                                <option value="">{{ __('tasks.filters.empty') }}</option>
                                @foreach ($labels as $label)
                                    <option
                                        value="{{ $label->id }}"
                                        @selected((string) request('filter.label_id') === (string) $label->id)
                                    >
                                        {{ $label->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('tasks.buttons.filter') }}
                            </button>

                            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                                {{ __('tasks.buttons.reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                @auth
                    <div class="mb-3">
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                            {{ __('tasks.buttons.create') }}
                        </a>
                    </div>
                @endauth

                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('tasks.fields.id') }}</th>
                        <th>{{ __('tasks.fields.name') }}</th>
                        <th>{{ __('tasks.fields.status') }}</th>
                        <th>{{ __('tasks.fields.labels') }}</th>
                        <th>{{ __('tasks.fields.creator') }}</th>
                        <th>{{ __('tasks.fields.assignee') }}</th>
                        <th>{{ __('tasks.fields.created_at') }}</th>
                        <th>{{ __('tasks.fields.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <td>{{ $task->id }}</td>
                            <td>
                                <a href="{{ route('tasks.show', $task) }}"
                                   class="text-primary text-decoration-underline link-offset-2 link-underline link-underline-opacity-25">
                                    {{ $task->name }}
                                </a>
                            </td>
                            <td>{{ $task->status->name }}</td>
                            <td>
                                @forelse ($task->labels as $label)
                                    <span class="badge bg-secondary">{{ $label->name }}</span>
                                @empty
                                    {{ __('tasks.empty_labels') }}
                                @endforelse
                            </td>
                            <td>{{ $task->creator->name }}</td>
                            <td>{{ $task->assignee?->name ?? __('tasks.empty_assignee') }}</td>
                            <td>{{ $task->created_at->format('d.m.Y') }}</td>
                            <td>
                                @auth
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-info btn-sm">
                                            {{ __('tasks.buttons.show') }}
                                        </a>

                                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning btn-sm">
                                            {{ __('tasks.buttons.edit') }}
                                        </a>

                                        @if ($task->created_by_id === auth()->id())
                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', 'confirm-task-deletion-{{ $task->id }}')"
                                            >
                                                {{ __('tasks.buttons.delete') }}
                                            </button>
                                        @endif
                                    </div>

                                    @if ($task->created_by_id === auth()->id())
                                        <x-modal name="confirm-task-deletion-{{ $task->id }}" focusable>
                                            <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                                                  class="p-6">
                                                @csrf
                                                @method('DELETE')

                                                <h2 class="text-lg font-medium text-gray-900">
                                                    {{ __('tasks.modal.delete_title') }}
                                                </h2>

                                                <p class="mt-1 text-sm text-gray-600">
                                                    {{ __('tasks.modal.delete_description') }}
                                                </p>

                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                        {{ __('tasks.modal.cancel') }}
                                                    </x-secondary-button>

                                                    <x-danger-button class="ms-3">
                                                        {{ __('tasks.modal.confirm') }}
                                                    </x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    @endif
                                @endauth
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
