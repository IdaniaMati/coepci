<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Models\Concurso;
use App\Models\Ganadores;
use App\Models\Registro;
use App\Http\Controllers\Controller;
use App\Imports\EmpleadosImport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{

    /* public function __construct()
    {
        $this->middleware('can: Modulo_Ajustes')->only('showDatosForm');
        // $this->middleware(['permission:Modulo_Ajustes'])->only('showDatosForm');
    } */

    public function showDatosForm()
    {
        return view('admin.datos');
    }

    public function obtenerEvento()
    {
        try {
            $concurso = Concurso::all();

            if ($concurso->isEmpty()) {
                return response()->json(['message' => 'No hay eventos en el sistema.']);
            }

            return response()->json(['concurso' => $concurso]);
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
            $archivo = $request->file('archivo');

            Excel::import(new EmpleadosImport, $archivo);

            return response()->json(['success' => true, 'message' => 'Empleados importados correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function vaciarBaseDatos()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            if (Registro::count() > 0) {
                Registro::truncate();
            }

            if (Empleado::count() > 0) {
                Empleado::truncate();
            } else {
                return response()->json(['success' => false, 'message' => 'La base de datos ya está vacía']);
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return response()->json(['success' => true, 'message' => 'Empleados y registros eliminados']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function agregarEvento(Request $request)
    {
        try {
            $request->validate([
                'descripcion' => 'required',
                'fechaIni1ronda' => 'required|date',
                'fechaIni2ronda' => 'required|date',
                'fechaFin' => 'required|date',
            ]);

            $nuevoEvento = new Concurso;
            $nuevoEvento->descripcion = $request->descripcion;
            $nuevoEvento->fechaIni1ronda = $request->fechaIni1ronda;
            $nuevoEvento->fechaIni2ronda = $request->fechaIni2ronda;
            $nuevoEvento->fechaFin = $request->fechaFin;
            $nuevoEvento->save();

            return response()->json(['success' => true, 'message' => 'Evento guardado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function verificarEventos()
    {
        $hayRegistros = Registro::count() > 0;

        return response()->json(['hayRegistros' => $hayRegistros]);
    }

    public function verificarDatosEnTablas()
    {
        try {
            $empleadosCount = Empleado::count() > 0;
            $registrosCount = Registro::count() > 0;

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
            // Verificar si hay registros en la tabla ganadores asociados al evento
            $ganadoresCount = DB::table('ganadores')->where('id_conc', $idEvento)->count();

            return response()->json(['tieneGanadores' => $ganadoresCount > 0]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hubo un error al verificar la existencia de ganadores.']);
        }
    }

    public function eliminarEvento($idEvento)
    {
        try {
            Concurso::findOrFail($idEvento)->delete();

            return response()->json(['success' => true, 'message' => 'Evento eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
