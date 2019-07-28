<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\HeaderHelper;
use App\Http\Controllers\Helpers\GeneratorHelper;
use App\Http\Controllers\Helpers\UserCRUD;

use App\Group;
use App\Union;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    /*
     * This method get all the Groups according:
     *  if (administrator) = the response are all the groups
     *  else = the response are just the groups belonging to the user
     */
    public function index()
    {
        try {
            if (Auth::user()->role_id == '1') {
                $data = Group::with(['union' => function($query) {
                    $query->select('id','name');
                    }])
                    ->with(['user' => function($query) {
                        $query->select('id', 'email');
                    }])
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $union_id = Union::select('id')->where('user_id', Auth::id())->first();
                $data = Group::select('id', 'name', 'user_id', 'union_id')
                    ->where('union_id', $union_id->id)
                    ->with(['union' => function($query) {
                        $query->select('id','name');
                    }])
                    ->with(['user' => function($query) {
                        $query->select('id', 'email');
                    }])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
        } catch (\Exception $e) {
            return response()->json([
                "data" => null,
            ], 404, HeaderHelper::$header);
        }
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    // TODO delete this method (route api/groups/{id})
    public function indexFromParams($user_id)
    {
        if (Auth::user()->role_id == '1') {
            $data = Group::with(['union' => function($query) {
                    $query->select('id','name');
                }])
                ->with(['user' => function($query) {
                    $query->select('id', 'email');
                }])
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $union_id = Union::select('id')->where('user_id', Auth::id())->first();
            $data = Group::select('id', 'name', 'user_id', 'union_id')
                ->where('union_id', $union_id->id)
                ->with(['union' => function($query) {
                    $query->select('id','name');
                }])
                ->with(['user' => function($query) {
                    $query->select('id', 'email');
                }])
                ->orderBy('id', 'DESC')
                ->get();
        }

        return response()->json([
            "data" => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        /*
         * The request must contain:
         *  - name
         *  - email
         *  - password
         *  - belongs_to = union_id = nullable if not an administrator
         */
        // get user ip
        // dd($request->getClientIp());
        // dd($request->json());
        try {
            $code = GeneratorHelper::code('GROUP');
            $user_id = UserCRUD::create($request, $code, '4')->id;
            if (Auth::user()->role_id == '1') {
                Group::create([
                    'name' => $request->name,
                    'union_id' => $request->belongs_to_id,
                    'user_id' => $user_id,
                    'code' => $code
                ]);
            } else {
                $union_id = Union::select('id')->where('user_id', Auth::id())->first();
                Group::create([
                    'name' => $request->name,
                    'union_id' => $union_id->id,
                    'user_id' => $user_id,
                    'code' => $code
                ]);
                Storage::disk('advertisement_image')->makeDirectory(Auth::user()->code.'/'.$code);
            }
        } catch (\Exception $e) {
            // TODO get more errors, example = the email is currently used
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
        // TODO use try and catch to evaluate future errors
        /*
         * The request contain:
         *  - id = group_id
         */
        $data = Group::where('id', $id)
            ->with(['union' => function($query) {
                $query->select('id','name');
            }])
            ->with(['user' => function($query) {
                $query->select('id', 'email');
            }])
            ->first();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function update(Request $request, Group $id)
    {
        /*
         * The request can contain:
         *  - name
         *  - email
         *  - password  = nullable
         */
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
        /*
         * The request contain:
         *  - id = group_id
         */
        UserCRUD::destroy($id);
    }
}
