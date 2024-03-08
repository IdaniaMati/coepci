<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
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
}
