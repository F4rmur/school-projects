<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LanguageController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => ['required', Rule::in(array_keys(config('app.supported_locales')))],
        ]);

        $request->session()->put('locale', $validated['locale']);

        return back();
    }
}
