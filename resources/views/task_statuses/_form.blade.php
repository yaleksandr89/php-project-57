<div class="mb-3">
    <label for="name" class="form-label">
        {{ $nameLabel }}
    </label>

    <input
        id="name"
        name="name"
        type="text"
        value="{{ old('name', $taskStatus->name ?? '') }}"
        class="form-control @error('name') is-invalid @enderror"
    >

    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-primary">
    {{ $buttonText }}
</button>
