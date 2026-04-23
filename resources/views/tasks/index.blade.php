<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('tasks.title') }}
        </h2>
    </x-slot>

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
                                <a href="{{ route('tasks.show', $task) }}">
                                    {{ $task->name }}
                                </a>
                            </td>
                            <td>{{ $task->status->name }}</td>
                            <td>{{ $task->creator->name }}</td>
                            <td>{{ $task->assignee?->name ?? __('tasks.empty_assignee') }}</td>
                            <td>{{ $task->created_at }}</td>
                            <td>
                                @auth
                                    <div class="d-flex gap-2">
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
                                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="p-6">
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
