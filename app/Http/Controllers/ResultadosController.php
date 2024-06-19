<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Empleado;
use App\Models\Concurso;
use App\Models\Ganadores;
use App\Models\Registro;
use App\Models\HistoricoVotos;
use App\Models\Dependencias;
use App\Models\Grupo;
use App\Models\Cargo;
use App\Http\Controllers\Controller;
use App\Helpers\MyHelper;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


use Illuminate\Http\Request;

class ResultadosController extends Controller
{
    public function showResultadosForm()
    {
        return view('admin.resultados');
    }

    public function uploadDocument(Request $request)
    {
        $request->merge(['ganador_id' => (int) $request->input('ganador_id')]);

        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
            'ganador_id' => 'required|integer|exists:ganadores,id'
        ]);

        try {
            $file = $request->file('file');
            $ganadorId = $request->input('ganador_id');

            $fileName = 'documento_' . $ganadorId . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('public/documentos', $fileName);

            // Elimina 'public/' del filePath para almacenar solo 'documentos/nombre_archivo.pdf' en la base de datos
            $filePath = substr($filePath, 7);

            // Actualiza el registro del ganador con la ruta del archivo
            Ganadores::where('id', $ganadorId)->update(['documento' => $filePath]);

            // Genera la URL completa del archivo
            $fileUrl = Storage::url($filePath);

            return response()->json(['success' => true, 'message' => 'Archivo subido exitosamente', 'url' => $fileUrl], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function detalleGanadores($id)
    {
        $ganador = Ganadores::where('id',$id)->get();
        return response()->json($ganador);
    }

    public function obtenerResultados(Request $request)
    {
        $ronda = $request->get('ronda', 1);

        $idDependencia = $request->get('idDependencia');

        $registros = Registro::where('ronda', $ronda)
            ->whereHas('concurso', function ($query) use ($idDependencia) {
                $query->where('id_depen', $idDependencia);
            })
            ->get();

        if ($registros->isEmpty()) {
            return response()->json(['message' => "No hay resultados para la Ronda $ronda."], 404);
        }

        $resultados = [];

        foreach ($registros as $registro) {
            $empleado = Empleado::find($registro->id_nom);

            $grupo = $registro->id_grup;

            if (!isset($resultados[$grupo])) {
                $resultados[$grupo] = [];
            }

            if (isset($resultados[$grupo][$empleado->id])) {
                $resultados[$grupo][$empleado->id]['votos']++;
            } else {
                $resultados[$grupo][$empleado->id] = [
                    'nombre' => $empleado->nombre,
                    'apellido_paterno' => $empleado->apellido_paterno,
                    'apellido_materno' => $empleado->apellido_materno,
                    'curp' => $empleado->curp,
                    'votos' => 1,
                ];
            }
        }

        foreach ($resultados as &$grupoResultados) {
            usort($grupoResultados, function ($a, $b) {
                return $b['votos'] - $a['votos'];
            });
        }

        return response()->json($resultados);
    }

    // public function obtenerGanadoresV(Request $request)
    // {
    //     try {
    //         $idDependenciaSeleccionada = $request->get('idDependencia');

    //         $ultimoConcurso = Concurso::where('id_depen', $idDependenciaSeleccionada)->latest()->first();

    //         if (!$ultimoConcurso) {
    //             return response()->json(['ganadores' => [], 'message' => 'Aún no hay votaciones para el concurso.']);
    //         }

    //         $ganadores = Ganadores::where('id_conc', $ultimoConcurso->id)
    //             ->select('ganadores.id_emp', 'ganadores.id_grup')
    //             ->get();

    //         $ganadoresAgrupados = $ganadores->groupBy('id_grup');

            //NO DESCOMENTAR

            //Agrupamos los ganadores por grupo y limitamos los resultados
            // $ganadoresAgrupados = $ganadores->groupBy('id_grup')->map(function ($grupo, $idGrupo) {
            //     if ($idGrupo == 1 || $idGrupo == 2) {
            //         return $grupo->take(2);
            //     } elseif ($idGrupo == 3) {
            //         return $grupo->take(3);
            //     }
            //     return $grupo;
            // });

            //Agrupamos los ganadores por grupo y limitamos los resultados
            // $ganadoresAgrupados = $ganadores->groupBy('id_grup')->map(function ($grupo, $idGrupo) {
            //     $maxGanadores = ($idGrupo == 1 || $idGrupo == 2) ? 2 : ($idGrupo == 3 ? 3 : 0);
            //     return $grupo->take($maxGanadores);
            // });

            // Convirtiendo la colección a array para asegurarnos que se retornan los resultados correctos
            //$ganadoresAgrupadosArray = $ganadoresAgrupados->toArray();

            // $ganadoresAgrupados = $ganadores->groupBy('id_grup')->map(function ($grupo) {
            //     $maxGanadores = [
            //         1 => 2, // Máximo 2 ganadores para el grupo 1
            //         2 => 2, // Máximo 2 ganadores para el grupo 2
            //         3 => 3, // Máximo 3 ganadores para el grupo 3
            //     ];

            //     return $grupo->take($maxGanadores[$grupo->first()->id_grup] ?? 0);
            // });

            //AQUI DESCOMENTAR

    //         return response()->json(['ganadores' => $ganadoresAgrupados]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function obtenerGanadoresV(Request $request)
    {
        try {

            $idDependenciaSeleccionada = $request->get('idDependencia');
            $ultimoConcurso = Concurso::where('id_depen', $idDependenciaSeleccionada)->latest()->first();

            if (!$ultimoConcurso) {
                return response()->json(['ganadores' => [], 'message' => 'Aún no hay votaciones para el concurso.']);
            }

            $ganadores = Ganadores::with('empleado')->where('id_conc', $ultimoConcurso->id)
                ->select('ganadores.id','ganadores.id_emp', 'ganadores.id_grup', 'ganadores.curp', 'ganadores.id_conc')

                // ->join('empleados', 'ganadores.id_emp', '=', 'empleados.id') // Unir con la tabla empleados
                // ->select(
                //     'ganadores.*',
                //     'ganadores.id',
                //     'ganadores.id_emp',
                //     'ganadores.id_grup',
                //     'empleados.curp',
                //     'empleados.nombre',
                //     'empleados.apellido_paterno',
                //     'empleados.apellido_materno'
                // )

                ->get();

                //dd($ganadores);

                $ganadoresAgrupados = $ganadores->groupBy('id_grup');

            return response()->json(['ganadores' => $ganadoresAgrupados, 'id_conc' => $ultimoConcurso->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function agregarExcepcion(Request $request){
        try {
            $user = Auth::user();
            //dd($request->all());
            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $request->validate([
                'id_emp' => 'required',
                'curp' => 'required',
                'id_grup' => 'required',
                'id_cargo' => 'required',
                'id_conc' => 'required',
                'documento' => 'required|file|mimes:pdf|max:8192',
            ]);

            $nuevoExcepcion = new Ganadores;
            $nuevoExcepcion->id_emp = $request->id_emp;
            $nuevoExcepcion->curp = strtoupper($request->curp);
            $nuevoExcepcion->id_grup = $request->id_grup;
            $nuevoExcepcion->id_conc = $user->concurso_id;
            $nuevoExcepcion->id_cargo = $request->id_cargo;

            if ($request->hasFile('documento')) {
                $file = $request->file('documento');
                $path = $file->store('documentos', 'public');
                $nuevoExcepcion->documento = $path;
            }

            $nuevoExcepcion->save();
            //MyHelper::registrarAccion('Se agrego el Caso excepcional: ' . $nuevoExcepcion->nombreCompleto);

            return response()->json(['success' => true, 'message' => 'Ganador guardado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function editarGanadores(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'ganador_id' => 'required|integer|exists:ganadores,id'
            ]);

            $ganador = Ganadores::findOrFail($request->input('id'));

            $ganador->id_conc = $request->input('id_conc');
            $ganador->id_emp = $request->input('id_emp');
            $ganador->curp = $request->input('curp');
            $ganador->id_grup = $request->input('id_grup');
            $ganador->id_cargo = $request->input('id_cargo');
            $ganador->documento = $request->input('documento');

            $ganador->save();

            MyHelper::registrarAccion('Se editó al empleado: ' . $ganador -> id_emp);

            return response()->json(['success' => true, 'message' => 'Empleado actualizado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function showResultadosDependencia(Request $request)
    {
        $user = Auth::user();
        $showDependenciaSelect = ($user && $user->id_depen === 1);
        $dependencias = $showDependenciaSelect ? Empleado::all() : [];

        // return response()->json([
        //     'showDependenciaSelect' => $showDependenciaSelect]);
        return response()->json([
            'showDependenciaSelect' => $showDependenciaSelect,
            'userDependencia' => $user ? $user->id_depen : null,
            'dependencias' => $dependencias,
        ]);
    }

    public function obtenerGrupos()
    {
        $grupos = Grupo::all();
        return response()->json(['success' => true, 'grupos' => $grupos]);
    }

    public function obtenerCargos()
    {
        $cargos = Cargo::all();
        return response()->json(['success' => true, 'cargos' => $cargos]);
    }


}
