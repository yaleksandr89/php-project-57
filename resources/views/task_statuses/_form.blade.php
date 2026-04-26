<div class="mb-3">
    {!! html()->label($nameLabel, 'name')->class('form-label') !!}

    {!! html()
        ->text('name')
        ->value(old('name', $taskStatus->name ?? ''))
        ->id('name')
        ->class('form-control' . ($errors->has('name') ? ' is-invalid' : '')) !!}

    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{!! html()->submit($buttonText)->class('btn btn-primary') !!}
