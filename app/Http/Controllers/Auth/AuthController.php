<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin() {
        if(Session::has('user')) {
            $userRole = Session::get('userRole');

            if ($userRole == 'admin') {
                return redirect()->route('admin.dashboard')->with('message', 'Sesión de administrador ya iniciada.');; 
            }; 

            if ($userRole == 'guest') {
                return redirect()->route('home')->with('message', 'Sesión de invitado ya iniciada.');; 
            }; 
        }

        $scripts = ['user.js'];
        return view('login', compact('scripts'));
    }


    public function login(Request $request) 
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if($email == '') {
            return Response()->json([
                'success' => false,
                'message' => 'Debes ingresar tu email para ingresar al sitio'
            ]);
        }

        if($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return Response()->json([
                'success' => false,
                'message' => 'El email ingresado es inválido'
            ]);
        }

        if ($email && strlen($email) > 60) {
            return Response()->json([
                'success' => false,
                'message' => 'La contraseña debe contener menos de 60 caracteres'
            ]);
        }

        if($password == '') {
            return Response()->json([
                'success' => false,
                'message' => 'Debes ingresar tu contraseña para ingresar al sitio'
            ]);
        }

        $user = User::where('email', $email)->first();

        if(!$user) {
            return Response()->json([
                'success' => false,
                'message' => 'No existe usuario registrado con ese email'
            ]);
        }

        if(!Hash::check($password, $user->password)) {
            return Response()->json([
                'success' => false,
                'message' => 'La contraseña ingresada es inválida'
            ]);
        }

        Auth::login($user);
        
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email,
            'role' => $user->role
        ];

        Session::put('user', $userData);
        Session::put('userRole', $user->role);

        return Response()->json([
            'success' => true,
            'user-role' => $user->role
        ]);
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }

    public function showSessionInfo() {
        $sessionData = Session::all();
        return response()->json($sessionData);
    }
}
