<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('labels.title') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                @auth
                    <div class="mb-3">
                        <a href="{{ route('labels.create') }}" class="btn btn-primary">
                            {{ __('labels.create') }}
                        </a>
                    </div>
                @endauth

                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('labels.fields.id') }}</th>
                        <th>{{ __('labels.fields.name') }}</th>
                        <th>{{ __('labels.fields.description') }}</th>
                        <th>{{ __('labels.fields.created_at') }}</th>
                        <th>{{ __('labels.fields.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($labels as $label)
                        <tr>
                            <td>{{ $label->id }}</td>
                            <td>{{ $label->name }}</td>
                            <td>{{ $label->description }}</td>
                            <td>{{ $label->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                @auth
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('labels.edit', $label) }}" class="btn btn-warning btn-sm">
                                            {{ __('labels.buttons.edit') }}
                                        </a>

                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm"
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'confirm-label-deletion-{{ $label->id }}')"
                                        >
                                            {{ __('labels.buttons.delete') }}
                                        </button>
                                    </div>

                                    <x-modal name="confirm-label-deletion-{{ $label->id }}" focusable>
                                        <form method="POST" action="{{ route('labels.destroy', $label) }}" class="p-6">
                                            @csrf
                                            @method('DELETE')

                                            <h2 class="text-lg font-medium text-gray-900">
                                                {{ __('labels.modal.delete_title') }}
                                            </h2>

                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ __('labels.modal.delete_description') }}
                                            </p>

                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                    {{ __('labels.modal.cancel') }}
                                                </x-secondary-button>

                                                <x-danger-button class="ms-3">
                                                    {{ __('labels.modal.confirm') }}
                                                </x-danger-button>
                                            </div>
                                        </form>
                                    </x-modal>
                                @endauth
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $labels->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
