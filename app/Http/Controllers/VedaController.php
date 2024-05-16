<?php

namespace App\Http\Controllers;
use App\Models\Imagenes;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use App\Helpers\MyHelper;

class VedaController extends Controller
{
    public function showVedaForm()
    {
        return view('admin.veda');
    }

    public function Obtenertodasimagenes($tipo = null)
    {
        $imagenesActivasQuery = Imagenes::where('estado', 1);
        $imagenesInactivasQuery = Imagenes::where('estado', 0);

        if ($tipo) {
            $imagenesActivasQuery->where('tipo', $tipo);
            $imagenesInactivasQuery->where('tipo', $tipo);
        }

        $imagenesActivas = $imagenesActivasQuery->get();
        $imagenesInactivas = $imagenesInactivasQuery->get();

        return response()->json(['activas' => $imagenesActivas, 'inactivas' => $imagenesInactivas]);
    }

    public function Obtenerimagenes($tipo = null)
    {
        if ($tipo) {
            $images = Imagenes::where('tipo', $tipo)->where('estado', 1)->get();
        } else {
            $images = Imagenes::where('estado', 1)->get();
        }

        return response()->json($images);
    }

    public function obtenerEstadoVeda()
    {
        try {
            $estadoVeda = Configuracion::value('veda');
            return response()->json(['estado' => $estadoVeda]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener el estado de la veda'], 500);
        }
    }

    public function cambiarEstadoVeda(Request $request)
    {
        $configuracion = Configuracion::first();

        if (!$configuracion) {
            return response()->json(['error' => 'No se encontró la configuración'], 404);
        }

        $nuevoEstado = $request->estado;
        $configuracion->veda = $request->estado;
        $configuracion->save();

        if ($nuevoEstado == 1) {
            $mensaje = 'Se activó la veda electoral';
        } else {
            $mensaje = 'Se desactivó la veda electoral';
        }

        MyHelper::registrarAccion($mensaje);


        return response()->json(['message' => 'Estado de la veda actualizado correctamente']);
    }

    public function cambiarEstadoImagen(Request $request)
    {
        $imagenes = Imagenes::all();

        foreach ($imagenes as $imagen) {
            if ($imagen->estado == 1) {
                $imagen->estado = 0;
            } else {
                $imagen->estado = 1;
            }

            $imagen->save();

        }

        return response()->json(['message' => 'Estados de las imágenes actualizados correctamente']);
    }


}
