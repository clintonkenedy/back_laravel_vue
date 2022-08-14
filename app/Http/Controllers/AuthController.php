<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class AuthController extends Controller
{
    //
    public function loginLaravel(Request $request){
        # auth
        //validar
        $request->validate([
           "email" => "required|email|string",
           "password" => "required|string"
        ]);

        //guardar
        $credenciales = request(['email','password']);
        if(!Auth::attempt($credenciales)){
            return response()->json([
                'mensaje' => 'No Autorizado',
            ],401);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal token');
        $token = $tokenResult->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
        //responder
    }

    public function registro(Request $request){
//        guardar user en la bd
        //validar
        $request->validate([
           "name" =>  "required",
           "email" =>  "required|unique:users|email",
           "password" =>  "required|string",
           "c_password" =>  "required|same:password",
        ]);
        //guardar
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->save();

//          transaciones laravel
//        try {
//            DB::insert();
//            DB::insert();
//            DB::insert();
//            DB::commit();
//        } catch (\Exception $e){
//            DB::rollback();
//        }
        //retornar
        return response()->json(["mensaje"=>"Usuario registrado"]);
    }

    public function perfil(){
        $user= Auth::user();
        return response()->json($user,200);
    }

    public function salir(){
        Auth::user()->tokens()->delete();

        return response()->json(["mensaje"=>"Tokens eliminados"]);
    }
}
