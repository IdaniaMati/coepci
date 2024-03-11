<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{

    public function showRoleForm()
    {
        return view('admin.roles');
    }

    public function obtenerRoles()
    {
        try {
            $role = Role::all();

            if ($role->isEmpty()) {
                return response()->json(['message' => 'No hay roles']);
            }

            return response()->json(['role' => $role]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function agregarRol(Request $request)
    {
        try {
            $name = $request->input('name');
            Role::create(['name' => $name]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function detalleRol($id)
    {

        $rol = Role::where('id',$id)->get();
        return response()->json($rol);

    }

    public function editarRol(Request $request)
    {
        $validaciones = $request->validate([
            'id' => 'required|integer',
            'name' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => $validaciones['name'],
            ];

            $editaRol = DB::table("roles")->where("id", $validaciones['id'])->update($data);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Rol Editado Exitosamente']);
        } catch (Exception $e) {
            $errors = $e->getMessage();
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $errors]);
        }
    }

    public function eliminarRol($id)
    {
        try {
            Role::findOrFail($id)->delete();

            return response()->json(['success' => true, 'message' => 'Rol eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function asignarpermisos(Request $request)
    {
        try {
            $idRol = $request->input('idRol');
            $selectedPermisos = $request->input('selectedPermisos');

            $role = Role::find($idRol);

            if (!$role) {
                return response()->json(['error' => 'Rol no encontrado.']);
            }

            $permissions = Permission::find($selectedPermisos);

            $role->syncPermissions($permissions);

            return response()->json(['success' => true, 'message' => 'Permisos asignados correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function obtenerPermisosRol($idRol)
    {
        $role = Role::findOrFail($idRol);
        $todosLosPermisos = Permission::all();
        $permisosAsignados = $todosLosPermisos->filter(function ($permiso) use ($role) {
            return $role->hasPermissionTo($permiso);
        });

        return response()->json(['permisos' => $permisosAsignados]);
    }
}
