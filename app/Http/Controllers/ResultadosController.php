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
            'file' => 'required|mimes:pdf|max:8048',
            'ganador_id' => 'required|integer|exists:ganadores,id'
        ]);

        try {
            $file = $request->file('file');
            $ganadorId = $request->input('ganador_id');

            //$fileName = 'documento_' . $ganadorId . '.' . $file->getClientOriginalExtension();
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('/documentos', $fileName);

            //$filePath = substr($filePath, 7);

            Ganadores::where('id', $ganadorId)->update(['documento' => $filePath]);

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

    public function editarGanadores(Request $request)
    {
        try {

            $request->validate([
                'id' => 'required|exists:ganadores,id',
                'id_emp' => 'required',
                'id_grup' => 'required|exists:grupos,id',
                'curp' => 'required|string',
                'id_cargo' => 'required|exists:cargos,id',
                'documento' => 'nullable|string',
            ]);

            $ganadorExistente = DB::table("ganadores")
                ->where("curp", $request['curp'])
                ->where("id", '!=', $request['id'])
                ->first();

            if ($ganadorExistente) {
                return response()->json(['success' => false, 'message' => 'Ya existe una empleado con esa CURP.']);
            }

            $ganador = Ganadores::findOrFail($request->input('id'));

            $ganador->id_emp = $request->input('id_emp');
            $ganador->curp = $request->input('curp');
            $ganador->id_grup = $request->input('id_grup');
            $ganador->id_cargo = $request->input('id_cargo');
            $ganador->documento = $request->input('documento');

            if ($request->hasFile('file')) {
                $path = $request->file('file')->store('documentos', 'public');
                $ganador->documento = $path;
            }

            $ganador->save();

            MyHelper::registrarAccion('Se editó al empleado: ' . $ganador -> id_emp);

            return response()->json(['success' => true, 'message' => 'Empleado actualizado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function agregarExcepcion(Request $request){
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $request->validate([
                'id_emp' => 'required',
                'curp' => 'required',
                'id_grup' => 'required',
                'id_cargo' => 'required',
                'documento' => 'required|file|mimes:pdf|max:8192',
            ]);

            $ultimoConcurso = Concurso::where('id_depen', $user->id_depen)
                                  ->orderBy('created_at', 'desc')
                                  ->first();

            if (!$ultimoConcurso) {
                return response()->json(['success' => false, 'message' => 'No se encontró un concurso para la dependencia del usuario'], 400);
            }

            $nuevoExcepcion = new Ganadores;
            $nuevoExcepcion->id_conc =  $ultimoConcurso->id;
            $nuevoExcepcion->id_emp = $request->id_emp;
            $nuevoExcepcion->curp = strtoupper($request->curp);
            $nuevoExcepcion->id_grup = $request->id_grup;
            $nuevoExcepcion->id_cargo = $request->id_cargo;

            if ($request->hasFile('documento')) {
                $file = $request->file('documento');
                $originalName = $file->getClientOriginalName();
                $path = $file->storeAs('documentos', $originalName);
                $nuevoExcepcion->documento = $originalName;
            }

            $nuevoExcepcion->save();
            //MyHelper::registrarAccion('Se agrego el Caso excepcional: ' . $nuevoExcepcion->nombreCompleto);

            return response()->json(['success' => true, 'message' => 'Ganador guardado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function calcularYGuardarGanadores()
    {
        $dependenciasConConcursos = Concurso::distinct('id_depen')->pluck('id_depen');

        $ganadoresCalculados = false;

        foreach ($dependenciasConConcursos as $idDependencia) {
            $ultimoConcurso = Concurso::where('id_depen', $idDependencia)->latest('id')->first();

            if ($ultimoConcurso) {
                $fechaFinConcurso = $ultimoConcurso->fechaFin;


                if (now() > $fechaFinConcurso) {
                    $this->calcularYGuardarGanadoresPorGrupo($ultimoConcurso->id, 1, 2);
                    $this->calcularYGuardarGanadoresPorGrupo($ultimoConcurso->id, 2, 2);
                    $this->calcularYGuardarGanadoresPorGrupo($ultimoConcurso->id, 3, 3);

                    //$this->copiarDatosAHistoricoVotos($idDependencia);

                    $ganadoresCalculados = true;
                }
            }
        }

        if ($ganadoresCalculados) {
            return response()->json(['message' => 'Ganadores calculados y guardados correctamente para todas las dependencias.']);
        } else {
            return response()->json(['message' => 'No hay concursos activos en ninguna dependencia.']);
        }
    }

    public function calcularYGuardarGanadoresPorGrupo($idConcurso, $idGrupo, $numGanadores)
    {
        $resultadosGrupo = DB::table('registros')
            ->select('id_nom', DB::raw('COUNT(id_nom) as votos'))
            ->where('ronda', 2)
            ->where('id_grup', $idGrupo)
            ->where('id_conc', $idConcurso)
            ->groupBy('id_nom')
            ->orderByDesc(DB::raw('COUNT(id_nom)'))
            ->take($numGanadores)
            ->get();

            //dd($resultadosGrupo);

            foreach ($resultadosGrupo as $resultado) {
                $idNom = $resultado->id_nom;
                $votos = $resultado->votos;

                $empleado = Empleado::find($idNom);

                if ($empleado) {
                    $nombreCompleto = $empleado->nombre . ' ' . $empleado->apellido_paterno . ' ' . $empleado->apellido_materno;
                    $curp = $empleado ->curp;

                    $existente = Ganadores::where('id_conc', $idConcurso)
                        ->where('id_grup', $idGrupo)
                        ->where('id_emp', $nombreCompleto)
                        ->exists();

                    if (!$existente) {
                        Ganadores::create([
                            'id_conc' => $idConcurso,
                            'id_grup' => $idGrupo,
                            'id_emp' => $nombreCompleto,
                            'curp' => $curp,
                            'votos' => $votos,
                        ]);
                    }
                }
            }
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

    public function obtenerGanadoresV(Request $request)
    {
        try {

            $idDependenciaSeleccionada = $request->get('idDependencia');
            $ultimoConcurso = Concurso::where('id_depen', $idDependenciaSeleccionada)->latest()->first();

            if (!$ultimoConcurso) {
                return response()->json(['ganadores' => [], 'message' => 'Aún no hay votaciones para el concurso.']);
            }

            $ganadores = Ganadores::with('empleado')->where('id_conc', $ultimoConcurso->id)
                ->select('ganadores.id','ganadores.id_emp', 'ganadores.id_grup', 'ganadores.curp', 'ganadores.id_cargo', 'ganadores.documento', 'ganadores.id_conc', 'ganadores.estado')

                // ->join('empleados', 'ganadores.id_emp', '=', 'empleados.id') // Unir con la tabla empleados
                // ->select(
                //     'ganadores.id',
                //     'ganadores.id_emp',
                //     'ganadores.id_grup',
                //     'empleados.curp',
                //     'empleados.nombre',
                //     'empleados.apellido_paterno',
                //     'empleados.apellido_materno',
                //     'ganadores.documento',
                //     'ganadores.id_conc',
                //     'ganadores.estado'
                //  )
                ->get();

                //dd($ganadores);

                $ganadoresAgrupados = $ganadores->groupBy('id_grup');

            return response()->json(['ganadores' => $ganadoresAgrupados, 'id_conc' => $ultimoConcurso->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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

    public function aprobarGanador($id)
    {
        $ganador = Ganadores::find($id);
        if ($ganador) {
            $ganador->estado = 1;
            $ganador->save();
            return response()->json(['message' => 'Ganador aprobado exitosamente.']);
        } else {
            return response()->json(['message' => 'Ganador no encontrado.'], 404);
        }
    }

    public function rechazarGanador($id)
    {
        $ganador = Ganadores::find($id);
        if ($ganador) {
            $ganador->estado = 0;
            $ganador->save();
            return response()->json(['message' => 'Ganador rechazado exitosamente.']);
        } else {
            return response()->json(['message' => 'Ganador no encontrado.'], 404);
        }
    }

    public function actualizarEstadoGanador(Request $request, $id)
    {
        try {
            $ganador = Ganadores::findOrFail($id);
            $estado = $request->input('estado');

            // Solo permitimos estados 1 (aprobado) y 2 (rechazado)
            if ($estado !== 1 && $estado !== 2) {
                return response()->json(['success' => false, 'message' => 'Estado no válido'], 400);
            }

            $ganador->estado = $estado;
            $ganador->save();

            return response()->json(['success' => true, 'message' => 'Estado del ganador actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
