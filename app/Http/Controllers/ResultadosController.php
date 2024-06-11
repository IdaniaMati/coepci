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
use App\Http\Controllers\Controller;
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

    public function obtenerGanadoresV(Request $request)
    {
        try {
            $idDependenciaSeleccionada = $request->get('idDependencia');

            $ultimoConcurso = Concurso::where('id_depen', $idDependenciaSeleccionada)->latest()->first();

            if (!$ultimoConcurso) {
                return response()->json(['ganadores' => [], 'message' => 'Aún no hay votaciones para el concurso.']);
            }

            $ganadores = Ganadores::where('id_conc', $ultimoConcurso->id)
                ->select('ganadores.id_emp', 'ganadores.id_grup')
                ->get();

            $ganadoresAgrupados = $ganadores->groupBy('id_grup');

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

            return response()->json(['ganadores' => $ganadoresAgrupados]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

}
