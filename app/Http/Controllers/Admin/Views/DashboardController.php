<?php

namespace App\Http\Controllers\Admin\Views;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Development;
use App\Models\User;
use App\Models\Post;

class DashboardController extends Controller
{
    public function showDashboard() {
        $propertiesCount = Property::count();
        $developmentsCount = Development::count();
        $lotsCount = 27; // temp value
        $postsCount = Post::count();
        $usersCount = User::count();

        $scripts = ['dashboard-misc.js'];
        return view('admin.dashboard', compact('scripts', 'propertiesCount', 'developmentsCount', 'lotsCount', 'postsCount', 'usersCount'));
    }
}
