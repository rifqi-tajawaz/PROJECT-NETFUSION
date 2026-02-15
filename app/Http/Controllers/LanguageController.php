<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch($locale)
    {
        // Validate the incoming locale
        // For now, supporting 'en' and 'id', but allowing others for future scaling
        // In a real app, you might check against a config array of supported locales
        $supportedLocales = ['en', 'id', 'es', 'fr', 'de']; // Example list

        if (!in_array($locale, $supportedLocales)) {
            // If the locale is not supported, just redirect back
            return redirect()->back();
        }

        // Store the locale in session
        Session::put('locale', $locale);

        // Also update app locale immediately for this request (though redirect happens next)
        App::setLocale($locale);

        return redirect()->back();
    }
}
