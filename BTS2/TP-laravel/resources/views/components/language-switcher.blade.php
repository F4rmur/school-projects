<form class="language-switcher" method="POST" action="{{ route('language.update') }}">
    @csrf
    <label class="visually-hidden" for="language">{{ __('ui.language.label') }}</label>
    <select id="language" name="locale" onchange="this.form.requestSubmit()">
        @foreach (config('app.supported_locales') as $locale => $language)
            <option value="{{ $locale }}" @selected(app()->getLocale() === $locale)>{{ $language }}</option>
        @endforeach
    </select>
</form>