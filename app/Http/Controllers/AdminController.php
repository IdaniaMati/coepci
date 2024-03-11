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

<<<<<<< HEAD
<<<<<<< HEAD
    public function export()
    {
        return Excel::download(new Registro, 'registros-sin-votar.xlsx');
    }

    public function obtenerRegistrosVotos()
    {
        try {
            $votosRonda1 = Registro::where('ronda', 1)->distinct('id_vot')->count('id_vot');
            $votosRonda2 = Registro::where('ronda', 2)->distinct('id_vot')->count('id_vot');
            $empleadosTotales = Empleado::count(); // Supongamos que tienes un modelo Empleado

            $empleadosNoVotaronRonda1 = $empleadosTotales - $votosRonda1;
            $empleadosNoVotaronRonda2 = $empleadosTotales - $votosRonda2;

            return response()->json([
                'votosRonda1' => $votosRonda1,
                'votosRonda2' => $votosRonda2,
                'empleadosNoVotaronRonda1' => $empleadosNoVotaronRonda1,
                'empleadosNoVotaronRonda2' => $empleadosNoVotaronRonda2,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener registros de votos por ronda'], 500);
        }
    }

    public function obtenerVotosRondas()
    {
        try {
            $empleados = Empleado::all();
            $resultados = [];

            foreach ($empleados as $empleado) {
                $votoRonda1 = Registro::where('id_vot', $empleado->id)->where('ronda', 1)->exists();
                $votoRonda2 = Registro::where('id_vot', $empleado->id)->where('ronda', 2)->exists();

                $resultados[] = [
                    'id_empleado' => $empleado->id,
                    'voto_ronda1' => $votoRonda1,
                    'voto_ronda2' => $votoRonda2,
                ];
            }

            return response()->json(['resultados' => $resultados], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los resultados de votos en las rondas.'], 500);
        }
    }

    /* ============Ajustes============ */
=======
>>>>>>> c95d93e1e3fcfc1d1f74fb86ebeb27577ab17f09
    public function obtenerEvento()
=======
    /* public function obtenerEvento()
>>>>>>> e7aac3898f62ebfc34d7341e3430dba7bf85f0ed
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
    } */

    public function obtenerEvento()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $depen_user = $user->id_depen;

            $eventos = Concurso::where('id_depen', $depen_user)
                ->get();

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

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            if (Registro::where('id_depen', $user->id_depen)->count() > 0) {
                Registro::where('id_depen', $user->id_depen)->delete();
            }

            if (Empleado::where('id_depen', $user->id_depen)->count() > 0) {
                Empleado::where('id_depen', $user->id_depen)->delete();
            } else {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
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

            $nuevoEvento = new Concurso;
            $nuevoEvento->descripcion = $request->descripcion;
            $nuevoEvento->fechaIni1ronda = $request->fechaIni1ronda;
            $nuevoEvento->fechaIni2ronda = $request->fechaIni2ronda;
            $nuevoEvento->fechaFin = $request->fechaFin;
            $nuevoEvento->id_depen = $depen_user;
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

            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $empleadosCount = Empleado::where('id_depen', $user->id_depen)->count() > 0;
            $registrosCount = Registro::where('id_depen', $user->id_depen)->count() > 0;

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
