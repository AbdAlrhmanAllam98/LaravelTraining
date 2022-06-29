<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
class AuthController extends Controller
{
    function login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        $cred=['email'=>$request->email,'password'=>$request->password];
        if(auth()->attempt($cred)){
            $user=auth()->user();
            return response()->json(['data'=>['token'=>$user->createToken('api-token')->plainTextToken,'user'=>new UserResource($user)],'status'=>false,'Message'=>'Check Email and Password']);
        }
        else{
            return response()->json(['data'=>[],'status'=>false,'Message'=>'Check Email and Password']);
        }
    }

    function logout(){
        $user=auth()->user();
        if($user->tokens()->delete()){
            return response()->json(['data'=>[],'status'=>true,'Message'=>'User is LoggedOut']);
        }
        else{
            return response()->json(['data'=>[],'status'=>false,'Message'=>'SomeThing is wrong ! ! !']);

        }
    }
}
