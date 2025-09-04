<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
class LanguageController extends Controller
{
    public function switchLang($locale)
    {
        $availableLocales = ['en', 'ar']; // add more as needed

        if (in_array($locale, $availableLocales)) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
    }
}
