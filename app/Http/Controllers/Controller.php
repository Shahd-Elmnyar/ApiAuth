<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

abstract class Controller
{

    public function register(Request $request){
        $validator = Validator::make($request->all(),[ // Validate the request
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255|unique:users,email',
            'password'=>'required|string|min:5|max:25|confirmed',
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('auth-token');
        return ['token'=>$token->plainTextToken];
    }

    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request...
        $request->user()->currentAccessToken()->delete();
        return response()->json(["SUCCESS('SUCCESS')"], 200);
    }

    // Login a user
    public function login(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:5|max:30'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 409);
        }
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json(['access_token' => $token], 201);
        } else {
            return response()->json(['msg' => 'Invalid credentials'], 401);
        }
    }
}

