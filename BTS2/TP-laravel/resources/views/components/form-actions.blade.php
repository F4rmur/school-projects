@props([
    'cancelHref' => null,
    'cancelLabel' => __('ui.common.cancel'),
    'submitLabel' => __('ui.common.save'),
])

<div {{ $attributes->merge(['class' => 'form-actions']) }}>
    @if ($cancelHref)
        <x-back-link :href="$cancelHref">{{ $cancelLabel }}</x-back-link>
    @endif

    <button class="button-link" type="submit">{{ $submitLabel }}</button>
</div>
