<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function changeUsername(Request $request)
    {
        try
        {
            $real_pwd = User::find(Auth::user()->id)->password;
            $pwd = $request->pwd;
            if (Hash::check($pwd, $real_pwd))
            {
                User::find(Auth::user()->id)->update([
                    'name' => $request->name,
                ]);
            } else {
                return response()->json([
                    "data" => false,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                "data" => false,
            ], 200);
        }
        return response()->json([
            "data" => true,
        ], 200);
    }

    public function changeEmail(Request $request)
    {
        try
        {
            $real_pwd = User::find(Auth::user()->id)->password;
            $pwd = $request->pwd;
            if (Hash::check($pwd, $real_pwd))
            {
                User::find(Auth::user()->id)->update([
                    'email' => $request->email,
                ]);
            } else {
                return response()->json([
                    "data" => false,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                "data" => false,
            ], 200);
        }
        return response()->json([
            "data" => true,
        ], 200);
    }

    public function changePassword(Request $request)
    {
        // $2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm
        try
        {
            $real_pwd = User::find(Auth::user()->id)->password;
            $pwd_old = $request->pwd_old;
            if (Hash::check($pwd_old, $real_pwd))
            {
                User::find(Auth::user()->id)->update([
                    'password' => bcrypt($request->pwd_new),
                ]);
            } else {
                return response()->json([
                    "data" => false,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                "data" => false,
            ], 200);
        }
        return response()->json([
            "data" => true,
        ], 200);
    }
}
