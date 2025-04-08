<?php

namespace App\Http\Controllers\Admin\Misc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 

class BlogController extends Controller
{
    public function index() {
        $scripts = ['blog.js'];
        return view('admin.blog.index', compact('scripts'));
    }

    public function create() {
        $scripts = ['blog.js'];
        return view('admin.blog.create', compact('scripts'));
    }

    public function store(Request $request) {
        // ...
    }

    public function edit($id) {
        $scripts = ['blog.js'];
        return view('admin.blog.edit', compact('scripts'));
    }

    public function update($id, Request $request) {
        // ...
    }

    public function delete($id) {
        // ...
    }

    public function search(Request $request) {
        $scripts = ['blog.js'];
        return view('admin.blog.index', compact('scripts'));
    }
}
