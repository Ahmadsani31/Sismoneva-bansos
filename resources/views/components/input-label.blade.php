@props(['value', 'req' => false])

<label {{ $attributes->merge(['class' => 'form-label']) }}>
    {{ $value ?? $slot }} @if ($req)
        <span class="text-danger">*</span>
    @endif

</label>
