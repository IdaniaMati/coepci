<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dependencias;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Helpers\MyHelper;


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

    public function obtenerUserDependencia()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $depen_user = $user->id_depen;

            return response()->json(['depen_user' => $depen_user]);
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

            MyHelper::registrarAccion('Se agrego al usuario: ' . $nuevoUsuario -> name);
            return response()->json(['success' => true, 'message' => 'Usuario agregado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function detalleUsuario($id)
    {
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
            'password' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $user = User::find($validaciones['id']);

            if ($request->has('password')) {
                if ($request->input('password') !== $user->password) {
                    $password = Hash::make($request->input('password'));
                    $data['password'] = $password;
                }
            }

            $data['name'] = $validaciones['name'];
            $data['email'] = $validaciones['email'];
            $data['id_depen'] = $validaciones['id_depen'];

            $editaUsuario = DB::table("users")->where("id", $validaciones['id'])->update($data);

            DB::commit();

            MyHelper::registrarAccion('Se editó al usuario: ' . $data ['name']);

            return response()->json(['success' => true, 'message' => 'Usuario Editado Exitosamente']);
        } catch (Exception $e) {
            $errors = $e->getMessage();
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $errors]);
        }
    }

    public function eliminarUsuario($idUser)
    {
        try {

            $user = User::findOrFail($idUser);

            if ($user->hasRole('Administrador')) {

                $role = Role::where('name', 'Administrador')->first();
                $administradores = $role->users()->count();

                if ($administradores <= 1) {
                    return response()->json(['error' => 'No se puede eliminar el único administrador']);
                }
            }
            MyHelper::registrarAccion('Se elimino al usuario: ' . $user -> name);
            $user->delete();

            return response()->json(['message' => 'Usuario eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el usuario'], 500);
        }
    }

    public function verificarAdministradores()
    {
        try {
            $role = Role::where('name', 'Administrador')->first();

            $administradores = $role->users()->count();

            return response()->json(['administradores' => $administradores]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al verificar los administradores'], 500);
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
