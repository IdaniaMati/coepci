<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;

class EmpleadoLoginController extends Controller
{    
    // Agrega un método para obtener la lista de empleados por grupo
    public function mostrarFormulario()
    {
        // Fetch the groups and employees data here
        $empleados = Empleado::get(); // Fetch all employees

        //dd($empleados);

        return view('empleado.formulario', compact('empleados'));
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

    public function loginForm() {
        return view( 'auth.login' );
    }

    public function loginPost( Request $request ) {

        $validator = Validator::make( $request->all(), [
            'no_id' => 'required|min:3',
            'contrasena' => 'required|min:3',
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'success' => false, 'errors' => $validator->errors() ] );
        }


        if ( Auth::attempt( [ 'no_id' => $request->no_id, 'contrasena' => $request->contrasena ]) ) {

                return response()->json( [ 'success' => true, 'message' => 'Acceso satisfactorio' ] );

        } else {
            return response()->json( [ 'success' => false, 'message' => 'Usuario o contraseña incorrecta' ] );
        }
    }

    //Cerrar session
    public function logoutGet() {

        Auth::logout();

        return redirect( '/' );
    }

    //Cerrar session
    public function logoutPost() {

        Auth::logout();

        return redirect( '/' );
    }



}
