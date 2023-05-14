<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|string'
        ]);
        $data['password'] = Hash::make($request->password);
        $save = new User($data);
        $save->save();
        return response()->json([
            'message' => 'success'
        ]);
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Unauthorized',
                'type' => 'failed'
            ]);
        } else {
            $token = $user->createToken('token')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        }
    }
}
