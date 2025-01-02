<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }} @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <select {{ $attributes->merge(['class' => 'form-select']) }}class="form-select" id="{{ $name }}"
        name="{{ $name }}" {{ $required ? 'required' : '' }}>
        <option value="" {{ old($name, $selected) === null ? 'selected' : '' }}>
            {{ $placeholder }}
        </option>
        @foreach ($options as $key => $option)
            <option value="{{ $key }}" {{ old($name, $selected) == $key ? 'selected' : '' }}>
                {{ $option }}
            </option>
        @endforeach
    </select>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
