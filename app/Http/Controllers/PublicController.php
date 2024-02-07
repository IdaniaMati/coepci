<?php

namespace App\Http\Controllers;
use App\Models\Registro;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function nominaciones()
    {
        return view('public.nominacion');
    }

    public function ResultadosVotacion()
    {
        return view('public.ronda');
    }

    public function historico()
    {
        return view('public.historico');
    }

    public function resultado(Request $request)
{
    $ronda = $request->get('ronda', 1);

    $registros = Registro::where('ronda', $ronda)->get();

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


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
