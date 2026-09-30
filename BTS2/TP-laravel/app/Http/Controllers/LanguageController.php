<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateLanguageRequest;
use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    public function update(UpdateLanguageRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->session()->put('locale', $validated['locale']);

        return back();
    }
}
