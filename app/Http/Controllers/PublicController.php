<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\Http\Controllers\Helpers\HeaderHelper;
use App\User;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function getAdvertisements()
    {
        $data = Advertisement::where('published', '1')->with(array('department' => function($q) {
            $q->select('id', 'name', 'user_id')->with(array('user' => function($user) {
                $user->select('id', 'code', 'name');
            }));
        }))->with(array('image' => function($q) {
            $q->select('id', 'path');
        }))->orderBy('id', 'DESC')->paginate(15);
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function belongsAdvertisements(Request $request)
    {
        // TODO
        $data = Advertisement::where('parent_code', $request->input('code'))->where('published', '1')->with(array('department' => function($q) {
            $q->select('id', 'name', 'user_id')->with(array('user' => function($user) {
                $user->select('id', 'code', 'name');
            }));
        }))->with(array('image' => function($q) {
            $q->select('id', 'path');
        }))->orderBy('id', 'DESC')->paginate(15);
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    // Input code = A598317965
    public function showAdvertisement(Request $request)
    {
        $data = Advertisement::where('code', $request->input('code'))->Where('published', '1')->with(array('department' => function($q) {
            $q->select('id', 'name', 'user_id')->with(array('user' => function($user) {
                $user->select('id', 'code', 'name');
            }));
        }))->with(array('image' => function($q) {
            $q->select('id', 'path');
        }))->orderBy('id', 'DESC')->get();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    // Input code = GBZKZQ, UclCUS
    public function favoriteAdvertisement(Request $request)
    {
        $data = Advertisement::whereIn('parent_code', explode(', ', $request->input('code')))->Where('published', '1')->with(array('department' => function($q) {
            $q->select('id', 'name', 'user_id')->with(array('user' => function($user) {
                $user->select('id', 'code', 'name');
            }));
        }))->with(array('image' => function($q) {
            $q->select('id', 'path');
        }))->orderBy('id', 'DESC')->get();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function bookmarksAdvertisement(Request $request)
    {
        $data = Advertisement::whereIn('code', explode(', ', $request->input('code')))->Where('published', '1')->with(array('department' => function($q) {
            $q->select('id', 'name', 'user_id')->with(array('user' => function($user) {
                $user->select('id', 'code', 'name');
            }));
        }))->with(array('image' => function($q) {
            $q->select('id', 'path');
        }))->orderBy('id', 'DESC')->get();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function searchAdvertisement(Request $request)
    {
        $value = $request->input('value');
        $data = Advertisement::where('title', 'LIKE', "%{$value}%")
            ->orWhere('code', 'LIKE', "%{$value}%")
            ->with(['department' => function($queryDepartment) {
                $queryDepartment->select('id', 'name', 'user_id')->with(['user' => function($user) {
                    $user->select('id', 'code', 'name');
                }]);
            }])
            ->with(['image' => function($q) {
                $q->select('id', 'path');
            }])
            ->orderBy('id', 'DESC')
            ->get();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function searchUserAdvertisement(Request $request)
    {
        $value = $request->input('value');
        $data = User::where('name', 'LIKE', "%{$value}%")
            ->orWhere('code', 'LIKE', "%{$value}%")
            ->orderBy('id', 'DESC')
            ->get();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }
}
