<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('task_statuses.index.title') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                @auth
                    <div class="mb-3">
                        <a href="{{ route('task_statuses.create') }}" class="btn btn-primary">
                            {{ __('task_statuses.index.create') }}
                        </a>
                    </div>
                @endauth

                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('task_statuses.index.id') }}</th>
                            <th>{{ __('task_statuses.index.name') }}</th>
                            <th>{{ __('task_statuses.index.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taskStatuses as $status)
                            <tr>
                                <td>{{ $status->id }}</td>
                                <td>{{ $status->name }}</td>
                                <td>
                                    @auth
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('task_statuses.edit', $status) }}" class="btn btn-warning btn-sm">
                                                {{ __('task_statuses.index.edit') }}
                                            </a>

                                            <form method="POST" action="{{ route('task_statuses.destroy', $status) }}">
                                                @csrf
                                                @method('DELETE')

                                                <a
                                                    href="{{ route('task_statuses.destroy', $status) }}"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="event.preventDefault(); if (confirm('{{ __('task_statuses.modal.delete_title') }}')) { this.closest('form').submit(); }"
                                                >
                                                    {{ __('task_statuses.index.delete') }}
                                                </a>
                                            </form>
                                        </div>
                                    @endauth
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $taskStatuses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
