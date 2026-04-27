<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('labels.edit') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                {{ html()->form('POST', route('labels.update', $label))->open() }}
                    @csrf
                    @method('PATCH')

                    @include('labels._form', [
                        'buttonText' => __('labels.buttons.update'),
                    ])
                {{ html()->form()->close() }}
            </div>
        </div>
    </div>
</x-app-layout>
