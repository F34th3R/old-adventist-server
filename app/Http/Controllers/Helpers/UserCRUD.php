<?php

namespace App\Http\Controllers\Helpers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Helpers\HeaderHelper;
use App\Http\Controllers\Helpers\CodeGenerator;

class UserCRUD
{
    public static function create(Request $request, $code)
    {
        $codeGenerator = new CodeGenerator();
        $user = User::create([
            'name' => $request->name,
            'email' =>$request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role,
            'code' => $code
        ]);
        return $user;
    }

    public static function update(Request $request)
    {
        User::where('id', $request->user_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    }

    public static function destroy($id)
    {
        try {
            User::where('id', $id->user_id)->delete();
        }
        catch (\Exception $e) {
            return response()->json([
                'response' => false,
            ],404);
        }
        return response()->json([
            "response" => true,
        ], 200, HeaderHelper::$header);
    }
}
