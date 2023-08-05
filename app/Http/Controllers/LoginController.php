<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function userLogin(Request $request)
    {
        $user = DB::table('logins')
            ->where('email', '=', "$request->email")
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
