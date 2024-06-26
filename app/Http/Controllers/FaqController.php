<?php

namespace App\Http\Controllers;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Manuales;
use Illuminate\Support\Facades\DB;
use App\Helpers\MyHelper;

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

            MyHelper::registrarAccion('Se agrego el manual: ' . $manual->nombre);

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

            $filePath = 'manuales/' . $manual->nombre;
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }

            $manual->delete();
            MyHelper::registrarAccion('Se eliminó el manual: ' . $manual->nombre );

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

            MyHelper::registrarAccion('Se edito el manual: ' . $manual->nombre);

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

    public function descargarManual($id)
    {
        $manual = Manuales::findOrFail($id);

        $filePath = storage_path('app/manuales/' . $manual->nombre);
        MyHelper::registrarAccion('Descargo el manual: ' . $manual->nombre);
        if (!file_exists($filePath)) {
            return response()->json(['message' => 'El archivo no existe'], 404);
        }

        return response()->download($filePath);
    }
    //End sección de manuales

    //Start sección de preguntas
    public function obtenerFaq()
    {
        $faqs = Faq::latest()->get();
        return response()->json($faqs);
    }

    public function agregarFaq(Request $request)
    {
        try {
            $request->validate([
                'pregunta' => 'required',
                'respuesta' => 'required',
            ]);

            $nuevoFaq = new Faq();
            $nuevoFaq->pregunta = $request->input('pregunta');
            $nuevoFaq->respuesta = $request->input('respuesta');
            $nuevoFaq->save();

            MyHelper::registrarAccion('Se agrego la pregunta: ' . $nuevoFaq -> pregunta);
            return response()->json(['success' => true, 'message' => 'FAQ agregada exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function detalleFaq($id)
    {
        $faq = Faq::find($id);
        return response()->json($faq);
    }

    public function editarFaq(Request $request)
    {
        $validaciones = $request->validate([
            'id' => 'required|integer',
            'pregunta' => 'required',
            'respuesta' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $data['pregunta'] = $validaciones['pregunta'];
            $data['respuesta'] = $validaciones['respuesta'];

            $editaFaq = DB::table("faq")->where("id", $validaciones['id'])->update($data);

            DB::commit();

            MyHelper::registrarAccion('Se editó la pregunta: ' . $data ['pregunta']);

            return response()->json(['success' => true, 'message' => 'La pregunta se ha editado Exitosamente']);
        } catch (\Exception $e) {
            $errors = $e->getMessage();
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $errors]);
        }
    }

    public function eliminarFaq($idFaq)
    {
        try {

            $faq = Faq::findOrFail($idFaq);
            MyHelper::registrarAccion('Se elimino la pregunta: ' . $faq->pregunta);

            $faq->delete();

            return response()->json(['success' => true, 'message' => 'Pregunta eliminada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    //End sección de preguntas
}
