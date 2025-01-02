<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }} @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group">
        @if ($prepend)
            <span class="input-group-text">{{ $prepend }}</span>
        @endif
        <input type="{{ $type }}" class="form-control " id="{{ $name }}" name="{{ $name }}"
            value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} />
        @if ($append)
            <span class="input-group-text">{{ $append }}</span>
        @endif
        <div class="invalid-feedback" id="error-{{ $name }}"></div>

    </div>
</div>
