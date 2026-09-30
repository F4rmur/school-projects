@props([
    'label' => null,
    'for' => null,
    'error' => null,
])

<div {{ $attributes->merge(['class' => 'form-field']) }}>
    @if ($label)
        <label for="{{ $for }}">{{ $label }}</label>
    @endif

    {{ $slot }}

    @if ($error)
        <small class="form-error">{{ $error }}</small>
    @endif
</div>
