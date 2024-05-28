<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Faq;
use Illuminate\Http\Request;
use App\Helpers\MyHelper;

class FaqController extends Controller
{
    public function showFaqForm()
    {
        return view('admin.faq');
    }

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

            MyHelper::registrarAccion('Se agrego la FAQ: ' . $nuevoFaq -> pregunta);
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
        } catch (Exception $e) {
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

}
