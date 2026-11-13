<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LanguageController extends Controller
{
    public function edit()
    {
        $availableLocales = config('app.supported_locales', []);

        return view('user.language-settings', compact('availableLocales'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'locale' => [
                'required',
                'string',
                Rule::in(array_keys(config('app.supported_locales', []))),
            ],
        ]);

        $user = Auth::user();
        $user->locale = $validated['locale'];
        $user->save();

        return redirect()
            ->route('language.settings')
            ->with('status', __('Saved.'));
    }
}