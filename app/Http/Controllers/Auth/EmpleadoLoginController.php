<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class EmpleadoLoginController extends Controller
{
    // Agrega un método para obtener la lista de empleados por grupo
    


    public function mostrarFormulario($id_grup)
    {
        // Fetch the groups and employees data here
        $grupos = // Fetch grupos data
        $empleados = Empleado::all(); // Fetch all employees

        return view('empleado.formulario', compact('grupos', 'empleados'));
    }
    
    //login de usuario
    public function showLoginForm()
    {
        return view('auth.empleado-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'curp' => 'required|string|max:18',
        ]);
    
        $credentials = [
            'curp' => $request->curp,
        ];
    
        $user = \App\Models\Empleado::where('curp', $request->curp)->first();
    
        // Check if user exists and manually log in
        if ($user) {
            Auth::guard('empleado')->login($user);
            return redirect('/empleado/formulario'); // Cambia la ruta según tus necesidades
        }
    
        return redirect()->back()->withErrors(['curp' => 'Credencial no válida']);
    }

    



}
