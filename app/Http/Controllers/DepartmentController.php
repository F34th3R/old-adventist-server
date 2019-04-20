<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\HeaderHelper;
use App\Http\Controllers\Helpers\CodeGenerator;
use App\Http\Controllers\Helpers\UserCRUD;

use App\Department;
use App\Http\Requests\DepartmentRequest;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        try {
            $data = Department::with(array('user' => function($query) {
                $query->select('id', 'name', 'email');
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
            $data = Department::with(array('user' => function($query) {
                $query->select('id', 'name', 'email');
            }))->where('user_id', $user_id)
                ->select('id', 'name', 'user_id')
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

    public function create()
    {
    }

    public function store(Request $request)
    {
        $codeGenerator = new CodeGenerator();
        try {
            if ($request->current_user_id == 1) {
                Department::create([
                    'name' => $request->name,
                    'user_id' => $request->user_id['user_id'],
                    'code' => $codeGenerator->generator('DEPARTMENTS')
                ]);
            }
            else {
                Department::create([
                    'name' => $request->name,
                    'user_id' => $request->current_user_id,
                    'code' => $codeGenerator->generator('DEPARTMENTS')
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
        $data = Department::with(array('user' => function($query) {
            $query->select('id', 'name', 'email');
        }))->where('id', $id)
            ->select('id', 'name', 'user_id')
            ->orderBy('id', 'DESC')
            ->first();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function edit($id)
    {
    }

    public function update(Request $request, Department $id)
    {
        try {
            if ($request->current_user_id == 1) {
                if ($request->user_id == null) {
                    $id->update([
                        'name' => $request->name,
                    ]);
                }
                else {
                    $id->update([
                        'name' => $request->name,
                        'user_id' => $request->user_id['user_id']
                    ]);
                }
            } else {
                $id->update([
                    'name' => $request->name,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
            ],404, HeaderHelper::$header);
        }
        return response()->json([
            "response" => true,
        ], 200, HeaderHelper::$header);
    }

    public function destroy(Department $id)
    {
        UserCRUD::destroy($id);
    }
}
