<?php

namespace App\Http\Controllers;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Manuales;

class FaqController extends Controller
{
    public function showFaqForm()
    {
        return view('admin.faq');
    }

    public function importarManual(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:8192',
        ]);

        $file = $request->file('file');

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'No se recibió ningún archivo',
            ], 400);
        }

        $timestamp = Carbon::now()->format('YmdHis');
        $originalName =$timestamp. '_' .$file->getClientOriginalName();

        try {
            $path = $file->storeAs('manuales', $originalName, 'local');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al almacenar el archivo: ' . $e->getMessage(),
            ], 500);
        }

        if ($path) {
            $this->registrarManual($originalName);

            return response()->json([
                'success' => true,
                'message' => 'Archivo subido exitosamente',
                'path' => $path,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al subir el archivo',
            ], 500);
        }
    }

    private function registrarManual($nombre)
    {
        try {
            $manual = new Manuales();
            $manual->nombre = $nombre;
            $manual->save();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el manual: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function listaManuales()
    {
        $manuales = Manuales::all();
        return response()->json($manuales);
    }

    public function eliminarManual($id)
    {
        try {
            $manual = Manuales::findOrFail($id);

            // Eliminar el archivo del sistema de archivos
            $filePath = 'manuales/' . $manual->nombre;
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            // Eliminar el registro de la base de datos
            $manual->delete();

            return response()->json([
                'success' => true,
                'message' => 'Manual eliminado exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el manual: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function editarManual(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:8192',
        ]);

        $file = $request->file('file');
        $manual = Manuales::findOrFail($id);

        // Eliminar el archivo anterior del sistema de archivos
        $filePath = 'manuales/' . $manual->nombre;
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        $timestamp = now()->format('Ymd_His');
        $originalName = $timestamp . '_' . $file->getClientOriginalName();

        try {
            $path = $file->storeAs('manuales', $originalName, 'local');
            $manual->nombre = $originalName;
            $manual->save();

            return response()->json([
                'success' => true,
                'message' => 'Archivo actualizado exitosamente',
                'path' => $path,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el archivo: ' . $e->getMessage(),
            ], 500);
        }
    }

}
