<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Dependencias;

class DependenciaController extends Controller
{
    public function showDependenciasForm()
    {
        return view('admin.dependencias');
    }

    public function obtenerDependencias()
    {
        try {
            $depen = Dependencias::all();

            if ($depen->isEmpty()) {
                return response()->json(['message' => 'No hay dependencias']);
            }

            return response()->json(['user' => $depen]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function agregarDependencia(Request $request)
    {
        try {
            $descripcion = $request->input('descripcion');

            //Validación
            $existeDependencia = Dependencias::where('descripcion', $descripcion)->exists();

            if($existeDependencia) {
                return response()->json(['success' => false, 'message' => 'Ya existe una dependencia con esa descripción'], 400);
            }

            Dependencias::create(['descripcion' => $descripcion]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function detalleDependencia($id)
    {

        $dependencia = Dependencias::where('id',$id)->get();
        return response()->json($dependencia);

    }

    public function editarDependencia(Request $request)
    {
        $validaciones = $request->validate([
            'id' => 'required|integer',
            'descripcion' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'descripcion' => $validaciones['descripcion'],
            ];

            $editaDependencia = DB::table("dependencias")->where("id", $validaciones['id'])->update($data);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Dependencia Editada Exitosamente']);
        } catch (Exception $e) {
            $errors = $e->getMessage();
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $errors]);
        }
    }

    public function eliminarDependencia($id)
    {
        try {
            Dependencias::findOrFail($id)->delete();

            return response()->json(['success' => true, 'message' => 'Dependencia eliminada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
