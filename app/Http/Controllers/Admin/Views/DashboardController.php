<?php

namespace App\Http\Controllers\Admin\Views;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function showDashboard() {
        $scripts = [''];
        return view('admin.dashboard', compact('scripts'));
    }
}
