<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\GeneratorHelper;
use App\Http\Controllers\Helpers\HeaderHelper;
use App\Http\Controllers\Helpers\UserCRUD;

use App\Union;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UnionController extends Controller
{
    public function index()
    {
        try {
            if (Auth::user()->role_id == '1') {
                $data = Union::with(array('user' => function($query) {
                    $query->select('id', 'email');
                }))->orderBy('id', 'DESC')->get();
            } else {
                return response()->json([
                    "error" => "You don't have the permission to access.",
                ], 403, HeaderHelper::$header);
            }
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e,
            ], 500, HeaderHelper::$header);
        }
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function store(Request $request)
    {
        try {
            if (Auth::user()->role_id == '1') {

                $validator = Validator::make($request->all(),[
                    'name' => 'required|min:5|unique:users,name',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|min:5',
                ]);
                if($validator->messages()->first()) {
                    return response()->json([
                        "response" => $validator->messages()
                    ], 400 , HeaderHelper::$header);
                }

                $code = GeneratorHelper::code('UNION');
                Union::create([
                    'name' => $request->name,
                    'user_id' => UserCRUD::create($request, $code, '3')->id,
                    'code' => $code
                ]);
                Storage::disk('advertisement_image')->makeDirectory($code);
            } else {
                return response()->json([
                    "response" => "You don't have the permission to access.",
                ], 403, HeaderHelper::$header);
            }

        } catch (\Exception $e) {
            $data = (object) ['code' => $code];
            Storage::disk('advertisement_image')->deleteDirectory($code);
            UserCRUD::destroy($data);
            return response()->json([
                'error' => $e,
            ],500, HeaderHelper::$header);
        }
        return response()->json([
            "response" =>  Union::where('code', $code)->with(array('user' => function($query) {
                $query->select('id', 'email');
            }))->orderBy('id', 'DESC')->get()
        ], 200, HeaderHelper::$header);
    }

    public function show($id)
    {
        try {
            if (Auth::user()->role_id == '1') {
                if (!Union::where('id', $id)->exists()) {
                    return response()->json([
                        "error" => "The current id: $id does't  exists.",
                    ], 404, HeaderHelper::$header);
                }
                $data = Union::with(['user' => function($query) {
                    $query->select('id', 'email');
                }])->where('id', $id)
                    ->first();
            } else {
                return response()->json([
                    "data" => "You don't have the permission to access.",
                ], 403, HeaderHelper::$header);
            }
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e,
            ], 500, HeaderHelper::$header);
        }

        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function update(Request $request, Union $id)
    {
        // TODO update password too
        try {
            if (Auth::user()->role_id == '1') {
//                $validator = Validator::make($request->all(),[
//                    'name' => 'min:5|unique:users,name',
//                    'email' => 'email|unique:users,email',
//                    'password' => 'min:5'
//                ]);
//                if($validator->messages()->first())
//                {
//                    return response()->json([
//                        "response" => $validator->messages()
//                    ], 400 , HeaderHelper::$header);
//                }
                $id->update([
                    'name' => $request->name,
                ]);
                UserCRUD::update($request);
            } else {
                return response()->json([
                    "data" => "You don't have the permission to access.",
                ], 403, HeaderHelper::$header);
            }
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e,
            ], 500, HeaderHelper::$header);
        }
        return response()->json([
            "response" => true,
        ], 200, HeaderHelper::$header);
    }

    public function destroy(Union $id)
    {
        try {
            if (Auth::user()->role_id == '1') {
                if (!$id->exists()) {
                    return response()->json([
                        "error" => "The current id: {$id->id} does't  exists.",
                    ], 404, HeaderHelper::$header);
                }
                UserCRUD::destroy($id);
            } else {
                return response()->json([
                    "data" => "You don't have the permission to access.",
                ], 403, HeaderHelper::$header);
            }
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e,
            ], 500, HeaderHelper::$header);
        }
        return response()->json([],200, HeaderHelper::$header);
    }
}
