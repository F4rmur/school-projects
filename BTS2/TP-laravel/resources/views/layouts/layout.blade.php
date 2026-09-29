<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title', __('ui.brand'))</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	@stack('head')
</head>
<body>
	<header class="site-header">
		<a class="brand" href="{{ route('accueil') }}">{{ __('ui.brand') }}<span>.</span></a>
		<nav class="site-nav" aria-label="{{ __('ui.navigation.main') }}">
			<a class="{{ request()->routeIs('absence.*') ? 'active' : '' }}" href="{{ route('absence.index') }}">{{ __('ui.navigation.absences') }}</a>
			<a class="{{ request()->routeIs('user.*') ? 'active' : '' }}" href="{{ route('user.index') }}">{{ __('ui.navigation.users') }}</a>
			@include('components.language-switcher')
			@auth
				<form method="POST" action="{{ route('logout') }}">
					@csrf
					<button class="nav-button" type="submit">{{ __('ui.navigation.logout') }}</button>
				</form>
			@endauth
		</nav>
	</header>

	<main class="page-shell">
		@if (session('success'))
			<div class="panel empty-state" role="status">{{ session('success') }}</div>
		@endif
		@if ($errors->any())
			<div class="panel empty-state" role="alert">{{ __('ui.validation.form_errors') }}</div>
		@endif
		@yield('content')
	</main>

	@stack('scripts')
</body>
</html>
