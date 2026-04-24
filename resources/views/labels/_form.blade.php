<div class="mb-3">
    <label for="name" class="form-label">
        {{ __('labels.fields.name') }}
    </label>

    <input
        id="name"
        name="name"
        type="text"
        value="{{ old('name', $label->name ?? '') }}"
        class="form-control @error('name') is-invalid @enderror"
    >

    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">
        {{ __('labels.fields.description') }}
    </label>

    <textarea
        id="description"
        name="description"
        class="form-control @error('description') is-invalid @enderror"
        rows="6"
    >{{ old('description', $label->description ?? '') }}</textarea>

    @error('description')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-primary">
    {{ $buttonText }}
</button>
