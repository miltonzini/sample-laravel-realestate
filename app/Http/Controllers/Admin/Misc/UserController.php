<?php

namespace App\Http\Controllers\Admin\Misc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index() {
        $users = User::orderBy('id')->paginate(20);
        $usersCount = $users->total();
        $scripts = ['user.js'];
        return view('admin.users.index', compact('scripts', 'users', 'usersCount'));
    }

    public function create() {
        $scripts = ['user.js'];
        return view('admin.users.create', compact('scripts'));
    }

    public function store(Request $request) {
        $messages = [
            'name.required' => 'Debes ingresar el nombre',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'name.max' => 'El nombre debe tener un máximo de 20 caracteres',
            'name.regex' => 'El nombre sólo puede contener letras y espacios',
            'surname.required' => 'Debes ingresar el apellido',
            'surname.min' => 'El apellido debe tener al menos 3 caracteres',
            'surname.max' => 'El apellido debe tener un máximo de 20 caracteres',
            'surname.regex' => 'El apellido sólo puede contener letras, espacios y comillas simples',
            'email.required' => 'Debes ingresar el email',
            'email.max' => 'El email debe tener un máximo de 60 caracteres',
            'email.email' => 'El email es inválido',
            'email.unique' => 'Ya existe un usuario registrado con ese email',
            'role.required' => 'Debes ingresar el rol',
            'password.required' => 'Debes ingresar el password',
            'password.min' => 'El password debe contener al menos 5 caracteres',
            'repeat-password.required' => 'Complete el campo "repetir contraseña"',
            'repeat-password.same' => 'Las contraseñas no coinciden',
        ];

        $nameRegex = '/^[a-zA-ZáéíóúàèìòùäëïöüÿâêîôûãõñçÁÉÍÓÚÀÈÌÒÙÄËÏÖÜŸÂÊÎÔÛÃÕÑÇ\s\'`´\-]+$/';
        $surnameRegex = '/^[a-zA-ZáéíóúàèìòùäëïöüÿâêîôûãõñçÁÉÍÓÚÀÈÌÒÙÄËÏÖÜŸÂÊÎÔÛÃÕÑÇ\s\'`´\-\.]+$/';
        
        $validations = $request->validate([
           'name' => ['required', 'min:2', 'max:25', 'regex:' . $nameRegex],
           'surname' => ['required', 'min:3', 'max:20', 'regex:' . $surnameRegex],
           'email' => 'required|max:60|email|unique:users',
           'role' => 'required',
           'password' => 'required|min:5',
           'repeat-password' => 'required|same:password',

        ], $messages);

        $name = $request->input('name');
        $surname = $request->input('surname');
        $email = $request->input('email');
        $role = $request->input('role');
        $password = $request->input('password');

        $userModel = new User();
        $userModel->name = $name;
        $userModel->surname = $surname;
        $userModel->email = $email;
        $userModel->role = $role;
        $userModel->password = Hash::make($password);
        $userModel->save();
        
        return Response()->json([
            'success' => true, 
            'message' => 'Usuario creado con éxito'
        ]);
    }

    public function edit($id) {
        $user = User::findOrFail($id);

        if (!$user) {
            return redirect()->route('admin.user.index')->with('error', 'El usuario no existe.');
        }

        $scripts = ['user.js'];
        return view('admin.users.edit', compact('scripts', 'user'));

    }

    public function update($id, Request $request) {
        $messages = [
            'name.required' => 'Debes ingresar el nombre',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'name.max' => 'El nombre debe tener un máximo de 20 caracteres',
            'name.regex' => 'El nombre sólo puede contener letras y espacios',
            'surname.required' => 'Debes ingresar el apellido',
            'surname.min' => 'El apellido debe tener al menos 3 caracteres',
            'surname.max' => 'El apellido debe tener un máximo de 20 caracteres',
            'surname.regex' => 'El apellido sólo puede contener letras, espacios y comillas simples',
            'email.required' => 'Debes ingresar el email',
            'email.max' => 'El email debe tener un máximo de 60 caracteres',
            'email.email' => 'El email es inválido',
            'email.unique' => 'Ya existe un usuario registrado con ese email',
            'role.required' => 'Debes ingresar el rol',
            'password.required' => 'Debes ingresar el password',
            'password.min' => 'El password debe contener al menos 5 caracteres',
            'repeat-password.required' => 'Complete el campo "repetir contraseña"',
            'repeat-password.same' => 'Las contraseñas no coinciden',
        ];

        $nameRegex = '/^[a-zA-ZáéíóúàèìòùäëïöüÿâêîôûãõñçÁÉÍÓÚÀÈÌÒÙÄËÏÖÜŸÂÊÎÔÛÃÕÑÇ\s\'`´\-]+$/';
        $surnameRegex = '/^[a-zA-ZáéíóúàèìòùäëïöüÿâêîôûãõñçÁÉÍÓÚÀÈÌÒÙÄËÏÖÜŸÂÊÎÔÛÃÕÑÇ\s\'`´\-\.]+$/';
        
        $validations = $request->validate([
           'name' => ['required', 'min:2', 'max:25', 'regex:' . $nameRegex],
           'surname' => ['required', 'min:3', 'max:20', 'regex:' . $surnameRegex],
           'email' => 'required|max:60|email|unique:users,email,'.$id.',id',
           'role' => 'required',
           'password' => 'nullable|min:5',
           'repeat-password' => 'nullable|same:password',

        ], $messages);

        $name = $request->input('name');
        $surname = $request->input('surname');
        $email = $request->input('email');
        $role = $request->input('role');
        $password = $request->input('password');

        if ($password) {
            User::where('id', $id)->update([
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'role' => $role,
                'password' => Hash::make($password)
            ]);
        } else {
            User::where('id', $id)->update([
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'role' => $role,
            ]);
        }

        if ($id == Session('user')['id']) {
            Session::put('user', [
                'id' => (int)$id,
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'role' => $role,
            ]);
            
            Session::put('userRole', $role);
        }
        
        return Response()->json([
            'success' => true, 
            'message' => 'Usuario actualizado con éxito'
        ]);
    }

    public function delete($id) {
        $user = User::where('id', $id)->first();

        if(!$user) {
            return Response()->json([
                'success' => false,
                'message' => 'No existe usuario registrado con dicho ID'
            ]);
        }

        if ($id == Session('user')['id']) {
            return Response()->json([
                'succes' => false,
                'message' => 'No puedes eliminarte a tí mismo del sistema'
            ]);
        }

        $user->delete();

        return Response()->json([
            'success' => true, 
            'message' => 'Se eliminó al usuario con éxito.'
        ]);
    }

    public function search(Request $request) {
        $search = $request->search;
        $users = User::where('name', 'like', "%$search%")
                    ->orWhere('surname', 'like', "%$search%")
                    ->paginate(20);
        $usersCount = $users->total();
        $scripts = [''];
        return view('admin.users.index', compact('users', 'usersCount', 'scripts', 'search'));
    }
}
