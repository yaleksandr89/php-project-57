<div class="mb-3">
    {!! html()->label(__('labels.fields.name'), 'name')->class('form-label') !!}

    {!! html()
        ->text('name')
        ->value(old('name', $label->name ?? ''))
        ->id('name')
        ->class('form-control' . ($errors->has('name') ? ' is-invalid' : '')) !!}

    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    {!! html()->label(__('labels.fields.description'), 'description')->class('form-label') !!}

    {!! html()
        ->textarea('description')
        ->value(old('description', $label->description ?? ''))
        ->id('description')
        ->class('form-control' . ($errors->has('description') ? ' is-invalid' : ''))
        ->rows(6) !!}

    @error('description')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{!! html()->submit($buttonText)->class('btn btn-primary') !!}
