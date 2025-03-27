<?php

namespace App\Http\Controllers\Public;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class FrontPageController extends Controller
{
    public function home() {
        // Log::channel('debug')->info('testing custom log');
        $scripts = [''];
        return view('home', compact('scripts'));
    }
    public function faq() {
        $scripts = [''];
        return view('faq', compact('scripts'));
    }

}


