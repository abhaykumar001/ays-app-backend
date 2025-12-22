<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SeoData;
use Illuminate\Support\Facades\Route;
class HomeController extends Controller
{
    public function home () {
        $pagemeta = SeoData::where('page_name', Route::current()->getName())->first();
        return view('frontend.home', compact('pagemeta'));
    }
    
}
