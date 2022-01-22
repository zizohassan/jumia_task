<?php

namespace App\Http\Controllers\Apis\Auth;

use App\Http\Controllers\Apis\BaseApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseApiController
{

    public function login(Request $request)
    {
        ///validation
        $request->validate([
            'email' => 'required|email|min:6|max:40',
            'password' => 'required|min:8|max:30',
            'device_name' => 'required|min:8|max:30',
        ]);

        /// check if user exists and have true password
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->response([], [
                'email' => ['The provided credentials are incorrect.'],
            ], 403);
        }

        /// standardize response
        return $this->response([
            'token' => $user->createToken($request->device_name)->plainTextToken
        ]);
    }

}
