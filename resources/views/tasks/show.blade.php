<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('tasks.show') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <table class="table">
                    <tbody>
                    <tr>
                        <th>{{ __('tasks.fields.id') }}</th>
                        <td>{{ $task->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('tasks.fields.name') }}</th>
                        <td>{{ $task->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('tasks.fields.description') }}</th>
                        <td>{{ $task->description ?? __('tasks.empty_description') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('tasks.fields.status') }}</th>
                        <td>{{ $task->status->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('tasks.fields.labels') }}</th>
                        <td>
                            @forelse ($task->labels as $label)
                                <span class="badge bg-secondary">{{ $label->name }}</span>
                            @empty
                                {{ __('tasks.empty_labels') }}
                            @endforelse
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('tasks.fields.creator') }}</th>
                        <td>{{ $task->creator->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('tasks.fields.assignee') }}</th>
                        <td>{{ $task->assignee?->name ?? __('tasks.empty_assignee') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('tasks.fields.created_at') }}</th>
                        <td>{{ $task->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
