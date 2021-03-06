<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|confirmed'
        ]);
        if($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors(),
                'success' => false,
                'message' => 'error in validation'
            ]);
        }

        $data = $validated->validated();
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);

        if($user) {
            return response()->json([
                'message' => 'registered successfully',
                'success' => true
            ], 201);
        }

        return response()->json([
            'errors' => 'fail to register',
            'message' => 'fail to register',
            'success' => false
        ]);
    }

    public function login(Request $request)
    {
        // $credentials = $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required'
        // ]);

    
        // if (Auth::attempt($credentials)) {
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'you have been successfully login',
        //         'user' => Auth::user()
        //     ]);
        // }



        // return response()->json([
        //     'success' => false,
        //     'message' => 'No User Found',
        //     'user' => null
        // ]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'success' => false,
            ]);
        }

        $data = $validator->validated();

        if(Auth::attempt($data)) {

            return response()->json([
                'success' => true,
                'errors' => null,
                'user' => Auth::user()

            ]);
        }

        return response()->json([
            'errors' => 'Password or Email is incorrect',
            'success' => false
        ]);

        
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'success' => true
        ], 200);
    }
}
