<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\HeaderHelper;
use App\Http\Controllers\Helpers\CodeGenerator;
use App\Http\Controllers\Helpers\UserCRUD;

use App\Http\Requests\UnionRequest;
use App\Union;
use App\User;
use Illuminate\Http\Request;

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
        $codeGenerator = new CodeGenerator();

        try {
            Union::create([
                'name' => $request->name,
                'user_id' => UserCRUD::create($request)->id,
                'code' => $codeGenerator->generator('UNIONS')
            ]);
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
        $data = Union::with(array('user' => function($query) {
            $query->select('id', 'email');
        }))->where('id', $id)
            ->first();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function update(Request $request, Union $id)
    {
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
