<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{    
    /**
     * index
     *
     * @param  mixed $request
     * @return void
     */
    public function index(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user= User::where('email', $request->email)->first();
        
            if (!$user || $request->password != $user->password) {
                return $response = [
                    'success'   => false,
                    'user' => null
                ];
            } else if ($request->password == $user->password) {
        
            $token = $user->createToken('ApiToken')->plainTextToken;
        
            $response = [
                'success'   => true,
                'user'      => $user,
                'token'     => $token
            ];
        }
        
        return response($response, 201);
    }
    
    /**
     * logout
     *
     * @return void
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'success'    => true
        ], 200);
    }

}