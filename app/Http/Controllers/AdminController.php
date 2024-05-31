<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Models\Concurso;
use App\Models\Ganadores;
use App\Models\Registro;
use App\Models\Dependencias;
use App\Http\Controllers\Controller;
use App\Imports\EmpleadosImport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Helpers\MyHelper;

class AdminController extends Controller
{
    public function showDatosForm()
    {
        return view('admin.datos');
    }

    public function obtenerEvento()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $depen_user = $user->id_depen;

            if ($depen_user == 1) {
                $eventos = Concurso::all();
            } else{
                $eventos = Concurso::where('id_depen', $depen_user)
                ->get();
            }

            return response()->json(['eventos' => $eventos]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function importarEmpleados(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls|max:10240', // Ajusta según tus necesidades
        ]);

        try {
                $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $archivo = $request->file('archivo');
            //dd($user->id_depen);
            Excel::import(new EmpleadosImport($user->id_depen), $archivo);

            MyHelper::registrarAccion('Importo un excel con empleados a Dashboard');
            return response()->json(['success' => true, 'message' => 'Empleados importados correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function vaciarBaseDatos()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $concursos = Concurso::where('id_depen', $user->id_depen)->get();

            if ($concursos->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No hay concursos para esta dependencia']);
            }

            $ultimoConcurso = $concursos->last();
            $fechaFinConcurso = $ultimoConcurso->fechaFin;

            if (strtotime($fechaFinConcurso) > strtotime('now')) {
                return response()->json(['success' => false, 'message' => 'No se puede vaciar la base de datos porque el último concurso aún no ha finalizado']);
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            $concursosDependencia = Concurso::where('id_depen', $user->id_depen)->pluck('id');

            if ($concursosDependencia->isNotEmpty()) {
                if (Registro::whereIn('id_conc', $concursosDependencia)->count() > 0) {
                    Registro::whereIn('id_conc', $concursosDependencia)->delete();
                }
            }

            if (Empleado::where('id_depen', $user->id_depen)->count() > 0) {
                Empleado::where('id_depen', $user->id_depen)->delete();
            } else {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                return response()->json(['success' => false, 'message' => 'La base de datos ya está vacía']);
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            MyHelper::registrarAccion('Elimino todos los empleados de Dashboard ');

            return response()->json(['success' => true, 'message' => 'Empleados y registros eliminados']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function agregarEvento(Request $request)
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $depen_user = $user->id_depen;

            $request->validate([
                'descripcion' => 'required',
                'fechaIni1ronda' => 'required|date',
                'fechaIni2ronda' => 'required|date',
                'fechaFin' => 'required|date',
            ]);

            $eventoExistente = DB::table("concursos")
            ->where("descripcion", $request['descripcion'])
            ->where("id", '!=', $request['id'])
            ->where("id_depen", $depen_user)
            ->first();

            if ($eventoExistente) {
                return response()->json(['success' => false, 'message' => 'Ya existe un evento con esa descripcion.']);
            }

            $nuevoEvento = new Concurso;
            $nuevoEvento->descripcion = $request->descripcion;
            $nuevoEvento->fechaIni1ronda = $request->fechaIni1ronda;
            $nuevoEvento->fechaIni2ronda = $request->fechaIni2ronda;
            $nuevoEvento->fechaFin = $request->fechaFin;
            $nuevoEvento->id_depen = $depen_user;
            $nuevoEvento->save();

            MyHelper::registrarAccion('Se agrego el evento: ' . $nuevoEvento -> descripcion);

            return response()->json(['success' => true, 'message' => 'Evento guardado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function verificarEventos()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $concursosDependencia = Concurso::where('id_depen', $user->id_depen)->pluck('id');

        $hayRegistros = 0;
            if ($concursosDependencia->isNotEmpty()) {
                $hayRegistros = Registro::whereIn('id_conc', $concursosDependencia)->count() > 0;
            }

        return response()->json(['hayRegistros' => $hayRegistros]);
    }

    public function verificarDatosEnTablas()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $concursosDependencia = Concurso::where('id_depen', $user->id_depen)->pluck('id');

            $empleadosCount = Empleado::where('id_depen', $user->id_depen)->count() > 0;

            $registrosCount = 0;
            if ($concursosDependencia->isNotEmpty()) {
                $registrosCount = Registro::whereIn('id_conc', $concursosDependencia)->count() > 0;
            }

            return response()->json([
                'hayRegistros' => $empleadosCount > 0 || $registrosCount > 0,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'error' => 'Hubo un error al verificar la existencia de registros.',
            ], 500);
        }
    }

    public function detalleEvento($id)
    {
        $evento = Concurso::where('id',$id)->get();
        return response()->json($evento);
    }

    public function editarEvento(Request $request)
    {
        $validaciones = $request->validate([
            'id' => 'required|integer',
            'descripcion' => 'required',
            'fechaIni1ronda' => 'required',
            'fechaIni2ronda' => 'required',
            'fechaFin' => 'required',
            'comentario' => 'required',
        ]);

        try {

            $concursoExistente = DB::table("concursos")
                ->where("descripcion", $validaciones['descripcion'])
                ->where("id", '!=', $validaciones['id'])
                ->first();

            if ($concursoExistente) {
                return response()->json(['success' => false, 'message' => 'Ya existe un evento con esa descripción.']);
            }

            DB::beginTransaction();

            $data = [
                'descripcion' => $validaciones['descripcion'],
                'fechaIni1ronda' => $validaciones['fechaIni1ronda'],
                'fechaIni2ronda' => $validaciones['fechaIni2ronda'],
                'fechaFin' => $validaciones['fechaFin'],
                'comentario' => $validaciones['comentario'],
            ];

            $EditaEvento = DB::table("concursos")->where("id", $validaciones['id'])->update($data);

            DB::commit();

            MyHelper::registrarAccion('Se edito el evento: ' . $data ['descripcion']);

            return response()->json(['success' => true, 'message' => 'Evento Editado Exitosamente']);
        } catch (Exception $e) {
            $errors = $e->getMessage();
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $errors]);
        }
    }

    public function verificarGanadores($idEvento)
    {
        try {
            $ganadoresCount = DB::table('ganadores')->where('id_conc', $idEvento)->count();

            return response()->json(['tieneGanadores' => $ganadoresCount > 0]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hubo un error al verificar la existencia de ganadores.']);
        }
    }

    public function eliminarEvento($idEvento)
    {
        try {

            $evento = Concurso::findOrFail($idEvento);
            $descripcion = $evento->descripcion;
            $evento->delete();

            MyHelper::registrarAccion('Se elimino el evento: ' . $descripcion);

            return response()->json(['success' => true, 'message' => 'Evento eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDependenciaEvento($id_depen)
    {
        $dependencia = Concurso::find($id_depen);
        return response()->json($dependencia);
    }
}
