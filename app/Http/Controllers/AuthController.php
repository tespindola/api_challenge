<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use Illuminate\support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use Exception;
use Validator;

class AuthController extends Controller{

    /**
     * Metodo para registrar un nuevo usuario
     * @param Request $request (name, email, password)
     * @return \Illuminate\Http\JsonResponse
     * @return message
     */
    public function register(Request $request){        
        // Validamos la informacion que llega en request
        $validator = User::validator($request->all());
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        
        try {
            // Guardamos el usuario
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $user->save();
        } catch (Exception $err) {
            // En caso de error abortamos y devolvemos el error
            return response()->json($err, 500);
        }

        return response()->json(['message' => 'User registered'], 200);
    }

    /**
     * Metodo el login de usuarios
     * @param Request $request (name, password)
     * @return \Illuminate\Http\JsonResponse
     * @return $post
     */
    public function login(Request $request){
        // Validamos los datos que se envian
        $rules = [
            'email' => 'required|exists:users,email|max:255',
            'password' => 'required|string|min:6|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials)){
            return response()->json(['message' => 'Credentials invalid'], 401);
        }

        // Logeamos al usuario y creamo el token correspondiente
        $user = Auth::user();
        $tokenResult = $user->createToken('Access token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addDays(1);
        $token->save();

        return response()->json([
            'user' => Auth::user(),
            'access_token' => $tokenResult->accessToken,
            'toke_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]);
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Logout success']);
    }

}
