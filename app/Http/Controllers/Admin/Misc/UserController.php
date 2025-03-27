<?php

namespace App\Http\Controllers\Admin\Misc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 

class UserController extends Controller
{
    public function index() {
        $scripts = ['users.js'];
        return view('admin.users.index', compact('scripts'));
    }

    public function create() {
        $scripts = ['users.js'];
        return view('admin.users.create', compact('scripts'));
    }

    public function store(Request $request) {
        $messages = [
            'name.required' => 'Debes ingresar tu nombre',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'name.max' => 'El nombre debe tener un máximo de 20 caracteres',
            'name.regex' => 'El nombre sólo puede contener letras y espacios',
            'surname.required' => 'Debes ingresar tu apellido',
            'surname.min' => 'El apellido debe tener al menos 3 caracteres',
            'surname.max' => 'El apellido debe tener un máximo de 20 caracteres',
            'surname.regex' => 'El apellido sólo puede contener letras, espacios y comillas simples',
            'email.required' => 'Debes ingresar tu email',
            'email.max' => 'El email debe tener un máximo de 60 caracteres',
            'email.email' => 'El email es inválido',
            'email.unique' => 'Ya existe un usuario registrado con ese email',
            'password.required' => 'Debes ingresar tu password',
            'password.min' => 'El password debe contener al menos 5 caracteres',
            'repeat-password.required' => 'Complete el campo "repetir contraseña"',
            'repeat-password.same' => 'Las contraseñas no coinciden',
        ];

        // Expresiones regulares para nombre y apellido
        $nameRegex = '/^[a-zA-Z\s´]+$/';
        $surnameRegex = '/^[a-zA-Z\s\'´]+$/';
        
        $validations = $request->validate([
           'name' => ['required', 'min:2', 'max:25', 'regex:' . $nameRegex],
           'surname' => ['required', 'min:3', 'max:20', 'regex:' . $surnameRegex],
           'email' => 'required|max:60|email|unique:users',
           'password' => 'required|min:5',
           'repeat-password' => 'required|same:password',

        ], $messages);

        $name = $request->input('name');
        $surname = $request->input('surname');
        $email = $request->input('email');
        $password = $request->input('password');
        $repeatPassword = $request->input('repeat-password');

        $userModel = new User();
        $userModel->name = $name;
        $userModel->surname = $surname;
        $userModel->email = $email;
        $userModel->password = Hash::make($password);
        $userModel->password = Hash::make($repeatPassword);
        $userModel->save();
        
        return Response()->json([
            'success' => true, 
            'message' => 'Usuario creado con éxito'
        ]);
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
