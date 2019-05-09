<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\HeaderHelper;
use App\Http\Controllers\Helpers\CodeGenerator;
use App\Http\Controllers\Helpers\UserCRUD;

use App\Group;
use App\Union;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        try {
            $data = Group::with(array('union' => function($query) {
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
        $union_id = Union::select('id')->where('user_id', $user_id)->first();

        $data = Group::with(array('union' => function($query) {
            $query->select('id','name');
        }))->with(array('user' => function($query) {
            $query->select('id', 'email');
        }))->where('union_id', $union_id->id)
            ->select('id', 'name', 'user_id', 'union_id')
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            "data" => $data,
        ], 200);
    }

    public function nameAndCode()
    {
        $data = Group::select('id', 'name', 'user_id')->orderBy('name', 'ASC')->get();

        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
        //! dreamsweetgirl
    }
    
    public function nameAndCodeByUser($user_id)
    {
        $data = Group::select('id', 'name', 'user_id')->where('user_id', $user_id)->orderBy('name', 'ASC')->get();

        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function nameAndCodeByUnion($union_id)
    {
        $data = Group::select('id', 'name', 'user_id')->where('union_id', $union_id)->orderBy('name', 'ASC')->get();

        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function store(Request $request)
    {
        $codeGenerator = new CodeGenerator();
        // TODO guardar las ip :D
//        dd($request->getClientIp());
//        dd($request->json());
        try {
            $code = $codeGenerator->generator('GROUPS');
            if ($request->current_user_id == 1) {
                Group::create([
                    'name' => $request->name,
                    'union_id' => $request->belongs_to_id,
                    'user_id' => UserCRUD::create($request, $code)->id,
                    'code' => $code
                ]);
            } else {
                $union_id = Union::select('id')->where('user_id', $request->current_user_id)->first();
                Group::create([
                    'name' => $request->name,
                    'union_id' => $union_id->id,
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
        $data = Group::with(array('union' => function($query) {
            $query->select('id','name');
        }))->with(array('user' => function($query) {
            $query->select('id', 'email');
        }))->where('id', $id)
            ->first();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function update(Request $request, Group $id)
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

    public function destroy(Group $id)
    {
        UserCRUD::destroy($id);
    }
}
