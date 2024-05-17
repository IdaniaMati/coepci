<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function showPermissionForm()
    {
        return view('admin.permisos');
    }

    public function obtenerPermisos()
    {
        try {
            $permiso = Permission::all();

            if ($permiso->isEmpty()) {
                return response()->json(['message' => 'No hay permisos']);
            }

            return response()->json(['permiso' => $permiso]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function agregarPermisos(Request $request)
    {
        try {
            $name = $request->input('name');
            Permission::create(['name' => $name]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function detallePermiso($id)
    {

        $permiso = Permission::where('id',$id)->get();
        return response()->json($permiso);

    }

    public function editarPermiso(Request $request)
    {
        $validaciones = $request->validate([
            'id' => 'required|integer',
            'name' => 'required',
        ]);

        try {
                $PermisoExistente = DB::table("permissions")
                ->where("name", $validaciones['name'])
                ->where("id", '!=', $validaciones['id'])
                ->first();

            if ($PermisoExistente) {
                return response()->json(['success' => false, 'message' => 'Ya existe un permiso con esa descripción.']);
            }
            DB::beginTransaction();

            $data = [
                'name' => $validaciones['name'],
            ];

            $editaPermiso = DB::table("permissions")->where("id", $validaciones['id'])->update($data);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Permiso Editado Exitosamente']);
        } catch (Exception $e) {
            $errors = $e->getMessage();
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $errors]);
        }
    }

    public function eliminarPermiso($id)
    {
        try {
            Permission::findOrFail($id)->delete();

            return response()->json(['success' => true, 'message' => 'Evento eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
