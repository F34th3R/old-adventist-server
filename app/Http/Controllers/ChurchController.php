<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\HeaderHelper;
use App\Http\Controllers\Helpers\CodeGenerator;
use App\Http\Controllers\Helpers\UserCRUD;

use App\Church;
use App\Group;
use Illuminate\Http\Request;

class ChurchController extends Controller
{
    public function index()
    {
        try {
            $data = Church::with(array('group' => function($query) {
                $query->select('id','name');
            }))->with(array('user' => function($query) {
                $query->select('id', 'email');
            }))->orderBy('id', 'DESC')
                ->get();
        } catch (\Exception $e) {
            return response()->json([
                "data" => null,
            ], 404, HeaderHelper::$header);
        }
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function indexFromParams($user_id)
    {
        try {
            $group_id = Group::select('id')->where('user_id', $user_id)->first();

            $data = Church::with(array('group' => function($query) {
                $query->select('id','name');
            }))->with(array('user' => function($query) {
                $query->select('id', 'email');
            }))->where('group_id', $group_id->id)
                ->select('id', 'name', 'user_id', 'group_id')
                ->orderBy('id', 'DESC')
                ->get();
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
        $data = Church::select('id', 'name', 'user_id')->orderBy('name', 'ASC')->get();

        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }
    
    public function nameAndCodeByUser($user_id)
    {
        $data = Church::select('id', 'name', 'user_id')->where('user_id', $user_id)->orderBy('name', 'ASC')->get();

        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function nameAndCodeByGroup($group_id)
    {
        $data = Church::select('id', 'name', 'user_id')->where('group_id', $group_id)->orderBy('name', 'ASC')->get();

        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function store(Request $request)
    {
        $codeGenerator = new CodeGenerator();

        try {
            $code = $codeGenerator->generator('CHURCHES');
            if ($request->current_user_id == 1) {
                Church::create([
                    'name' => $request->name,
                    'group_id' => $request->belongs_to_id,
                    'user_id' => UserCRUD::create($request, $code)->id,
                    'code' => $code
                ]);
            }
            else {
                $group_id = Group::select('id')->where('user_id', $request->current_user_id)->first();
                Church::create([
                    'name' => $request->name,
                    'group_id' => $group_id->id,
                    'user_id' => UserCRUD::create($request, $code)->id,
                    'code' => $code
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "response" => false
            ], 200, HeaderHelper::$header);
        }
        return response()->json([
            "response" => true
        ], 200, HeaderHelper::$header);
    }

    public function show($id)
    {
        $data = Church::with(array('group' => function($query) {
            $query->select('id','name');
        }))->with(array('user' => function($query) {
            $query->select('id', 'email');
        }))->where('id', $id)
            ->first();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function update(Request $request, Church $id)
    {
        dd($request->getContent());
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

    public function destroy(Church $id)
    {
        UserCRUD::destroy($id);
    }
}
