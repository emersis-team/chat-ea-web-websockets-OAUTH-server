<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        $token = $user->createToken('API Token')->accessToken;

        return response([ 'user' => $user, 'token' => $token]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($data)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        return response(['user' => auth()->user(), 'token' => $token]);

    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
