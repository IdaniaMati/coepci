<?php

namespace App\Http\Controllers;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function showFaqForm()
    {
        return view('admin.faq');
    }

    public function importarManual(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $file = $request->file('file');

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'No se recibió ningún archivo',
            ], 400);
        }

        $originalName = time() . '_' . $file->getClientOriginalName();

        try {
            $path = $file->storeAs('manuales', $originalName, 'local');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al almacenar el archivo: ' . $e->getMessage(),
            ], 500);
        }

        if ($path) {
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

}


