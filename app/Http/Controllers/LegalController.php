<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LegalController extends Controller
{
    public function privacyPolicy(): View
    {
        return view('legal.privacy-policy');
    }

    public function terms(): View
    {
        return view('legal.terms');
    }
}
