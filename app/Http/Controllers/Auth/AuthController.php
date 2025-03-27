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
            return redirect()->route('admin.dashboard')->with('message', 'Sesión ya iniciada.');;
        }

        $scripts = ['users.js'];
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

        $verifyEmail = User::where('email', $email)->first();

        if(!$verifyEmail) {
            return Response()->json([
                'success' => false,
                'message' => 'No existe usuario registrado con ese email'
            ]);
        }

        if(!Hash::check($password, $verifyEmail->password)) {
            return Response()->json([
                'success' => false,
                'message' => 'La contraseña ingresada es inválida'
            ]);
        }

        Auth::login($verifyEmail);
        
        $userData = [
            'id' => $verifyEmail->id,
            'name' => $verifyEmail->name,
            'surname' => $verifyEmail->surname,
            'email' => $verifyEmail->email
        ];

        $roleData = 'admin';

        Session::put('user', $userData);
        Session::put('userRole', $roleData);

        return Response()->json([
            'success' => true,
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
