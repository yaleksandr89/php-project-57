<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('task_statuses.create.title') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <form method="POST" action="{{ route('task_statuses.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            {{ __('task_statuses.create.name') }}
                        </label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                        >

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        {{ __('task_statuses.create.submit') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
