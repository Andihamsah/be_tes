<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $role = Role::where(['guard_name' => 'web', 'name' => 'buyer'])->get();
        $user->assignRole($role);
        $token = $user->createToken('LaravelAuthApp')->accessToken;

        return ResponseHelper::success(['token' => $token, 'role' => $role], 'register berhasil', 200);
    }

    public function login(Request $request)
    {
        // $validator = Validator::validate([
        //     'email' => 'required|email|string',
        //     'password' => 'required|string',
        // ]);

        // if ($validator->fails()) {
        //     return ResponseHelper::error($validator->errors(), 400);
        // }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('LaravelAuthApp')->accessToken;
            $role = $user->roles()->first();
            $shop = Auth::user()->shop;
            return ResponseHelper::success(['token' => $token, 'role' => $role->name, 'shop' => $shop->id], 'login berhasil', 200);
        } else {
            return ResponseHelper::error(['error' => 'Unauthorized'], 401);
        }
    }
}
