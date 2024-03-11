<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    

    public function showUserForm()
    {
        return view('admin.usuarios');
    }

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

    public function detalleUsuario($id)
    {

        /* $user = User::where('id',$id)->get();
        return response()->json($user); */
        $user = User::find($id);
        return response()->json($user);
    }

    public function editarUsuario(Request $request)
    {
        $validaciones = $request->validate([
            'id' => 'required|integer',
            'name' => 'required',
            'email' => 'required',
            'id_depen' => 'required',
            'password' => 'required',

        ]);

        try {
            DB::beginTransaction();

            if ($request->has('password')) {

                $password = Hash::make($request->input('password'));
                $data = array(
                    'name' => $validaciones['name'],
                    'email' => $validaciones['email'],
                    'id_depen' => $validaciones['id_depen'],
                    'password' => $password,
                );
            }else{
                $data = array(
                    'name' =>$validaciones['name'],
                    'email' => $validaciones['email'],
                );
            }

            $editaUsuario = DB::table("users")->where("id", $validaciones['id'])->update($data);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Usuario Editado Exitosamente']);
        } catch (Exception $e) {
            $errors = $e->getMessage();
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $errors]);
        }
    }

    public function eliminarUsuario($id)
    {
        try {
            User::findOrFail($id)->delete();

            return response()->json(['success' => true, 'message' => 'Rol eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function asignarRoles(Request $request)
    {
        try {
            $idUser = $request->input('idUser');
            $selectedRoles = $request->input('selectedRoles');

            $user = User::find($idUser);

            if (!$user) {
                return response()->json(['error' => 'Rol no encontrado.']);
            }

            $roles = Role::find($selectedRoles);

            $user->syncRoles($roles);

            return response()->json(['success' => true, 'message' => 'Permisos asignados correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerRolesUsuario($idUser)
    {
        $user = User::findOrFail($idUser);
        $todosLosRoles = Role::all();
        $rolesAsignados = $todosLosRoles->filter(function ($role) use ($user) {
            return $user->hasAnyRole($role);
        });

        return response()->json(['roles' => $rolesAsignados]);
    }
}
