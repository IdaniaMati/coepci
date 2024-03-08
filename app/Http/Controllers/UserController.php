<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function obtenerUsers()
    {
        try {
            $user = User::all();

            if ($user->isEmpty()) {
                return response()->json(['message' => 'No hay usuarios']);
            }

            return response()->json(['user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function agregarUsuario(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'id_depen' => 'required',
                'password' => 'required',
            ]);

            $nuevoUsuario = new User();
            $nuevoUsuario->name = $request->input('name');
            $nuevoUsuario->email = $request->input('email');
            $nuevoUsuario->id_depen = $request->input('id_depen');
            $nuevoUsuario->password = Hash::make($request->input('password'));
            $nuevoUsuario->save();

            return response()->json(['success' => true, 'message' => 'Usuario agregado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
