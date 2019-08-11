<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\HeaderHelper;
use App\Http\Controllers\Helpers\GeneratorHelper;
use App\Http\Controllers\Helpers\UserCRUD;

use App\Church;
use App\Group;
use App\Union;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ChurchController extends Controller
{
    /*
     * This method get all the Churches according:
     *  if (administrator) = the response are all the groups
     *  else = the response are just the groups belonging to the user
     */
    public function index()
    {
        try {
            if (Auth::user()->role_id == '1') {
                $data = Church::with(['group' => function($query) {
                    $query->select('id','name');
                    }])
                    ->with(['user' => function($query) {
                        $query->select('id', 'email');
                    }])
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $group_id = Group::select('id')->where('user_id', Auth::id())->first();
                $data = Church::select('id', 'name', 'user_id', 'group_id')
                    ->where('group_id', $group_id->id)
                    ->with(['group' => function($query) {
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
                "error" => $e,
            ], 500, HeaderHelper::$header);
        }
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    // TODO delete this method (route api/churches/{id})
    public function indexFromParams($user_id)
    {
        try {
            if (Auth::user()->role_id == '1') {
                $data = Church::with(['group' => function($query) {
                    $query->select('id','name');
                }])
                    ->with(['user' => function($query) {
                        $query->select('id', 'email');
                    }])
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $group_id = Group::select('id')->where('user_id', Auth::id())->first();
                $data = Church::select('id', 'name', 'user_id', 'group_id')
                    ->where('group_id', $group_id->id)
                    ->with(['group' => function($query) {
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
                "error" => $e,
            ], 500, HeaderHelper::$header);
        }
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    /*
     * This method create a group and an user
     */
    public function store(Request $request)
    {
        /*
         * The request must contain:
         *  - name
         *  - email
         *  - password
         *  - belongs_to = group_id = nullable if not an administrator
         */
        try {
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

            $code = GeneratorHelper::code('CHURCH');
            $user_id = UserCRUD::create($request, $code, '5')->id;
            if ($request->current_user_id == 1) {
                Church::create([
                    'name' => $request->name,
                    'group_id' => $request->belongs_to_id,
                    'user_id' => $user_id,
                    'code' => $code
                ]);
            }
            else {
                $group = Group::select('id', 'union_id')->where('user_id', Auth::id())->first();
                $union = Union::select('id', 'code')->where('id', $group->union_id)->first();
                Church::create([
                    'name' => $request->name,
                    'group_id' => $group->id,
                    'user_id' => $user_id,
                    'code' => $code
                ]);
                Storage::disk('advertisement_image')->makeDirectory($union->code.'/'.Auth::user()->code.'/'.$code);
            }
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e,
            ], 500, HeaderHelper::$header);
        }
        return response()->json([
            "response" => true
        ], 200, HeaderHelper::$header);
    }

    public function show($id)
    {
        /*
         * The request contain:
         *  - id = church_id
         */
        try {
            if (!Church::where('id', $id)->exists()) {
                return response()->json([
                    "error" => "The current id: $id don't exists.",
                ], 404, HeaderHelper::$header);
            }
            $data = Church::with(array('group' => function($query) {
                $query->select('id','name');
            }))->with(array('user' => function($query) {
                $query->select('id', 'email');
            }))->where('id', $id)
                ->first();
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e,
            ], 500, HeaderHelper::$header);
        }

        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function update(Request $request, Church $id)
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
                'error' => $e,
            ],500, HeaderHelper::$header);
        }
        return response()->json([
            "response" => true,
        ], 200, HeaderHelper::$header);
    }

    public function destroy(Church $id)
    {
        /*
         * The request contain:
         *  - id = church_id
         */
        try {
            if (!$id->exists()) {
                return response()->json([
                    "error" => "The current id: $id does't  exists.",
                ], 404, HeaderHelper::$header);
            }
            UserCRUD::destroy($id);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e,
            ], 500, HeaderHelper::$header);
        }
        return response()->json([],200, HeaderHelper::$header);
    }
}
