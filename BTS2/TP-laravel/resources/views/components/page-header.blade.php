@props([
    'eyebrow' => null,
    'title' => null,
    'intro' => null,
    'right' => null,
])

<div {{ $attributes->merge(['class' => 'page-heading']) }}>
    <div>
        @if ($eyebrow)
            <p class="eyebrow">{{ $eyebrow }}</p>
        @endif

        @if ($title)
            <h1>{{ $title }}</h1>
        @endif

        @if ($intro)
            <p class="intro">{{ $intro }}</p>
        @endif
    </div>

    @if ($right)
        <div>{{ $right }}</div>
    @endif
</div>
