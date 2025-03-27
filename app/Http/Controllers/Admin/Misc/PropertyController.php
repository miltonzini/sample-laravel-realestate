<?php

namespace App\Http\Controllers\Admin\Misc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 

class PropertyController extends Controller
{
    public function index() {
        $scripts = ['property.js'];
        return view('admin.properties.index', compact('scripts'));
    }

    public function create() {
        $scripts = ['property.js'];
        return view('admin.properties.create', compact('scripts'));
    }

    public function store(Request $request) {
        // ...
    }

    public function edit($id) {
        // ...
    }

    public function update($id, Request $request) {
        // ...
    }

    public function delete($id) {
        // ...
    }

    public function search(Request $request) {
        // ...
    }
}
