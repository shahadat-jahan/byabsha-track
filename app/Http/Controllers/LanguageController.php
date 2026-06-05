<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale): \Illuminate\Http\RedirectResponse
    {
        $supported = config('app.available_locales', ['en', 'bn']);

        if (in_array($locale, $supported)) {
            Session::put('locale', $locale);
        }

        return redirect()->back()->withHeaders([
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }
}
