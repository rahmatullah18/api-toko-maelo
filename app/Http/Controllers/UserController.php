<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function registerUser(Request $request)
    {
        try {
            $user_name = $request->input('email');
            $password = $request->input('password');
            $user_no_wa = $request->input('noWa');
            $user_address = '-';

            User::create([
                'user_name' => $user_name,
                'password' => $password,
                'user_no_wa' => $user_no_wa,
                'user_address' => $user_address,
            ]);

            return response()->json([
                'message' => 'Data berhasil di input'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 400);
        }
    }

    public function loginUser(Request $request)
    {
        $user = DB::table('users')
            ->where('user_name', '=', "$request->email")
            ->where('password', '=', "$request->password")
            ->first();

        if ($user) {
            // Data ditemukan, kirimkan respons API berhasil dengan data user
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
            ], 200);
        } else {
            // Data tidak ditemukan, kirimkan respons API gagal
            return response()->json([
                'message' => 'Login failed. Invalid email or password.',
            ], 401);
        }
    }
}
