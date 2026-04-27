<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('task_statuses.edit.title') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                {{ html()->form('POST', route('task_statuses.update', $taskStatus))->open() }}
                    @csrf
                    @method('PATCH')

                    @include('task_statuses._form', [
                        'nameLabel' => __('task_statuses.edit.name'),
                        'buttonText' => __('task_statuses.edit.submit'),
                    ])
                {{ html()->form()->close() }}
            </div>
        </div>
    </div>
</x-app-layout>
