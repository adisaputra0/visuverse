<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "username" => "required|unique:users",
            "password" => "required|min:6",
        ]);
        if($validator->fails()){
            return response()->json([
                "message" => "Invalid field",
                "errors" => $validator->errors(),
            ], 403);
        }
        User::create($request->all());
        return response()->json([
            "message" => "Register success"
        ], 201);
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            "username" => "required",
            "password" => "required",
        ]);
        if($validator->fails()){
            return response()->json([
                "message" => "Invalid field",
                "errors" => $validator->errors(),
            ], 403);
        }
        if(Auth::attempt($request->all())){
            $user = User::where("username", $request->username)->first();
            return response()->json([
                "message" => "Login success",
                "token" => $user->createToken("auth_token")->plainTextToken,
                "user" => $user,
            ]);
        }
        return response()->json([
            "message" => "Wrong username or password",
        ], 401);
    }
    public function logout(){
        $user = User::where("username", auth()->user()->username)->first();
        $user->tokens()->delete();
        return response()->json([
            "message" => "Logout success",
        ]);
    }
    public function profile(){
        return response()->json([
            "message" => "Get profile success",
            "user" => auth()->user(),
        ]);
    }
}
