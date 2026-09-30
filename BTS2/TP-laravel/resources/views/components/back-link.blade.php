@props([
    'href',
    'label' => null,
])

<a {{ $attributes->merge(['class' => 'back-link', 'href' => $href]) }}>
    {{ $slot ?: ($label ?? __('ui.common.back')) }}
</a>
