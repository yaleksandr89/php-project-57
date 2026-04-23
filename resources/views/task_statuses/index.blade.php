<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('task_statuses.index.title') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <div class="mb-3">
                    <a href="{{ route('task_statuses.create') }}" class="btn btn-primary">
                        {{ __('task_statuses.index.create') }}
                    </a>
                </div>

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
                                    <a href="{{ route('task_statuses.edit', $status) }}" class="btn btn-warning btn-sm">
                                        {{ __('task_statuses.index.edit') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
