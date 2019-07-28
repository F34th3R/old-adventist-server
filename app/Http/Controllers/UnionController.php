<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\HeaderHelper;
use App\Http\Controllers\Helpers\GeneratorHelper;
use App\Http\Controllers\Helpers\UserCRUD;

use App\Http\Requests\UnionRequest;
use App\Union;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UnionController extends Controller
{

    public function index()
    {
        try {
            $data = Union::with(array('user' => function($query) {
                $query->select('id', 'email');
            }))->orderBy('id', 'DESC')->get();
        } catch (\Exception $e) {
            return response()->json([
                "data" => null,
            ], 404, HeaderHelper::$header);
        }
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function nameAndCode()
    {
        $data = Union::select('id', 'name', 'user_id')->orderBy('name', 'ASC')->get();

        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }
    
    public function nameAndCodeByUser($user_id)
    {
        $data = Union::select('id', 'name', 'user_id')->where('user_id', $user_id)->orderBy('name', 'ASC')->get();

        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function store(UnionRequest $request)
    {
        try {
            $code = GeneratorHelper::code('UNION');
            Union::create([
                'name' => $request->name,
                'user_id' => UserCRUD::create($request, $code, '3')->id,
                'code' => $code
            ]);
            Storage::disk('advertisement_image')->makeDirectory($code);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
            ],404, HeaderHelper::$header);
        }
        return response()->json([
            "response" => true
        ], 200, HeaderHelper::$header);
    }

    public function show($id)
    {
        $data = Union::with(['user' => function($query) {
            $query->select('id', 'email');
        }])->where('id', $id)
            ->first();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function update(Request $request, Union $id)
    {
        // TODO update password too
        try {
            $id->update([
                'name' => $request->name,
            ]);
            UserCRUD::update($request);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
            ],404, HeaderHelper::$header);
        }
        return response()->json([
            "response" => true,
        ], 200, HeaderHelper::$header);
    }

    public function destroy(Union $id)
    {
        UserCRUD::destroy($id);
    }
}
