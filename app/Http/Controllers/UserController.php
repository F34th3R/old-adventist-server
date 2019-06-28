<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\HeaderHelper;
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
                ], 200, HeaderHelper::$header);
            }
        } catch (\Exception $e) {
            return response()->json([
                "data" => false,
            ], 200, HeaderHelper::$header);
        }
        return response()->json([
            "data" => true,
        ], 200, HeaderHelper::$header);
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
                ], 200, HeaderHelper::$header);
            }
        } catch (\Exception $e) {
            return response()->json([
                "data" => false,
            ], 200, HeaderHelper::$header);
        }
        return response()->json([
            "data" => true,
        ], 200, HeaderHelper::$header);
    }

    public function changePassword(Request $request)
    {
        $real_pwd = User::find(Auth::user()->id)->password;
        $pwd_old = $request->pwd_old;
        $isPass = Hash::check($pwd_old, $real_pwd);
        if ($isPass)
        {
            try
            {
                User::find(Auth::user()->id)->update([
                    'password' => bcrypt($request->pwd_new),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    "response" => false,
                ], 404, HeaderHelper::$header);
            }
            return response()->json([
                "response" => true,
            ], 200, HeaderHelper::$header);
        } else {
            return response()->json([
                "response" => false,
            ], 200, HeaderHelper::$header);
        }

    }
}
