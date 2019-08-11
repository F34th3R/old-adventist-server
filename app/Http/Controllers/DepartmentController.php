<?php

namespace App\Http\Controllers;

use App\Church;
use App\Group;
use App\Http\Controllers\Helpers\FolderHelper;
use App\Http\Controllers\Helpers\HeaderHelper;
use App\Http\Controllers\Helpers\GeneratorHelper;

use App\Department;
use App\Union;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DepartmentController extends Controller
{
    /*
     * This method get all the Departments according:
     *  if (administrator) = the response are all the groups
     *  else = the response are just the groups belonging to the user
     */
    public function index()
    {
        try {
            if (Auth::user()->role_id == '1') {
                $data = Department::with(['user' => function($query) {
                    $query->select('id', 'name', 'email');
                    }])
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $data = Department::select('id', 'name', 'user_id')
                    ->where('user_id', Auth::id())
                    ->with(['user' => function($query) {
                        $query->select('id', 'name', 'email');
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

    public function indexFromParams($user_id)
    {
        try {
            if (Auth::user()->role_id == '1') {
                $data = Department::with(['user' => function($query) {
                    $query->select('id', 'name', 'email');
                }])
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $data = Department::select('id', 'name', 'user_id')
                    ->where('user_id', Auth::id())
                    ->with(['user' => function($query) {
                        $query->select('id', 'name', 'email');
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

    public function store(Request $request)
    {
        /*
         * The request must contain:
         *  - name
         *  - belongs_to = user_id = nullable if not an administrator
         */
        try {
            $code = GeneratorHelper::code('DEPARTMENT');
            if (Auth::user()->role_id == '1') {
                $belongs_to = $request->input('belongs_to');
                $user = User::select('id','code')->where('id', $belongs_to)->first();
                Department::create([
                    'name' => $request->name,
                    'user_id' => $belongs_to,
                    'code' => $code
                ]);
                Storage::disk('advertisement_image')->makeDirectory($user->code.'/'.$code);
            }
            else {
                Department::create([
                    'name' => $request->name,
                    'user_id' => Auth::id(),
                    'code' => $code
                ]);
                // Create folder
                FolderHelper::createDelete($code, true);
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
         *  - id = department_id
         */
        try {
            if (!Department::where('id', $id)->exists()) {
                return response()->json([
                    "error" => "The current id: $id does't  exists.",
                ], 404, HeaderHelper::$header);
            }
            $data = Department::select('id', 'name', 'user_id')
                ->where('id', $id)
                ->with(['user' => function($query) {
                    $query->select('id', 'name', 'email');
                }])
                ->orderBy('id', 'DESC')
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

    public function update(Request $request, Department $id)
    {
        /*
         * The request can contain:
         *  - name
         *  - belongs_to = user_id = nullable if not an administrator
         */
        try {
            if (Auth::user()->role_id == '1') {
                if ($request->input('belongs_to') == null) {
                    $id->update([
                        'name' => $request->name,
                    ]);
                }
                else {
                    $id->update([
                        'name' => $request->name,
                        'user_id' => $request->input('belongs_to')
                    ]);
                }
            } else {
                $id->update([
                    'name' => $request->name,
                ]);
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

    public function destroy(Department $id)
    {
        try {
            if (!Department::where('code', $id->code)->exists()) {
                return response()->json([
                    "error" => "The current id: {$id->id} does't  exists.",
                ], 404, HeaderHelper::$header);
            }
            FolderHelper::createDelete($id->code, false);
            $id->delete();
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e,
            ], 500, HeaderHelper::$header);
        }
        return response()->json([],200, HeaderHelper::$header);

    }
}
