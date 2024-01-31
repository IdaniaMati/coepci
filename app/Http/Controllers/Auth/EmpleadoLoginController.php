<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;


class EmpleadoLoginController extends Controller
{

    //login de usuario
    public function showLoginForm()
    {
        return view('auth.empleado-login');
    }



    public function login(Request $request)
    {
        $curp = $request->input('curp');

        $user = Empleado::where('curp', $curp)->first();

        if ($user) {
            Auth::guard('empleado')->login($user);

            return response()->json(['success' => true, 'redirect' => route('VotacionEmpleado')]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function VotacionEmpleado()
    {
        return view('auth.votacion');
    }

    public function logout()
    {
        Auth::guard('empleado')->logout();
        return redirect()->route('empleado.login');
    }

    /* // Agrega un método para obtener la lista de empleados por grupo
    public function mostrarFormulario()
    {
        // Fetch the groups and employees data here
        $empleados = Empleado::get(); // Fetch all employees

        //dd($empleados);

        return view('empleado.formulario', compact('empleados'));
    } */






    public function loginForm() {
        return view( 'auth.login' );
    }

    /* public function loginPost( Request $request )
    {

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
    } */


    /* public function destroy()
    {
        Auth::guard('empleado')->logout();

        return redirect('/')->with('status', 'Sesión cerrada correctamente');
    } */

    /* public function logout(Request $request)
    {
        Auth::guard('empleado')->logout();

        $request->session()->invalidate();

        return redirect('/');
    } */


}
