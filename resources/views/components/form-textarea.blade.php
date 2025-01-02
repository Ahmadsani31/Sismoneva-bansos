<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }} @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <textarea class="form-control" id="{{ $name }}" name="{{ $name }}" placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }} rows="{{ $rows }}">{{ old($name, $value) }}</textarea>
    <div class="invalid-feedback" id="error-{{ $name }}"></div>
</div>
