<?php

namespace App\Http\Controllers\Helpers;

use App\Church;
use App\Group;
use App\Union;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserCRUD
{
    public static function create(Request $request, $code, $role = '2')
    {
        $user = User::create([
            'name' => $request->name,
            'email' =>$request->email,
            'password' => bcrypt($request->password),
            'role_id' => $role,
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

    public static function destroy($data)
    {
        try {
            if ($data->code[0] == 'U') {
                if (User::where('id', $data->user_id)->exists()) {
                    $groups = Group::select('id', 'user_id')
                        ->where('union_id', $data->id)
                        ->get();
                    $churches = Church::select('id', 'user_id')
                        ->whereIn('group_id', IteratorHelper::iterator_Id($groups))
                        ->get();
                    User::whereIn('id', IteratorHelper::iterator_User_id($churches))->delete();
                    User::whereIn('id', IteratorHelper::iterator_User_id($groups))->delete();
                    User::where('id', $data->user_id)->delete();

                    //? Delete the union folder
                    Storage::disk('advertisement_image')->deleteDirectory($data->code);
                }

            } elseif ($data->code[0] == 'G') {
                if (User::where('id', $data->user_id)->exists()) {
                    $union = Union::select('id', 'code')
                        ->where('id', $data->union_id)
                        ->first();
                    $churches = Church::select('id', 'user_id')
                        ->where('group_id', $data->id)
                        ->get();
                    User::whereIn('id', IteratorHelper::iterator_User_id($churches))->delete();
                    User::where('id', $data->user_id)->delete();

                    //? Delete the group folder
                    Storage::disk('advertisement_image')->deleteDirectory($union->code.'/'.$data->code);
                }

            } elseif ($data->code[0] == 'C') {
                $group = Group::select('id', 'code', 'union_id')
                    ->where('id', $data->group_id)
                    ->first();
                $union = Union::select('id', 'code')
                    ->where('id', $group->union_id)
                    ->first();
                User::where('id', $data->user_id)->delete();

                //? Delete the church folder
                Storage::disk('advertisement_image')->deleteDirectory($union->code.'/'.$group->code.'/'.$data->code);

            } else {
                $response = "The user: $data->code do not exist";
                return response()->json([
                    'response' => $response,
                ],404);
            }
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
