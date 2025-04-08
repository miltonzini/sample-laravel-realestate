<?php

namespace App\Http\Controllers\Admin\Misc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 

class DevelopmentController extends Controller
{
    public function index() {
        $scripts = ['development.js'];
        return view('admin.developments.index', compact('scripts'));
    }

    public function create() {
        $scripts = ['development.js'];
        return view('admin.developments.create', compact('scripts'));
    }

    public function store(Request $request) {
        // ...
    }

    public function edit($id) {
        $scripts = ['development.js'];
        return view('admin.developments.edit', compact('scripts'));
    }

    public function update($id, Request $request) {
        // ...
    }

    public function delete($id) {
        // ...
    }

    public function search(Request $request) {
        $scripts = ['development.js'];
        return view('admin.developments.index', compact('scripts'));
    }
}
