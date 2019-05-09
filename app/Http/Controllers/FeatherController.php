<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\Church;
use App\Group;
use App\Http\Controllers\Helpers\HeaderHelper;
use App\Union;
use App\User;
use Illuminate\Http\Request;

class FeatherController extends Controller
{
    public function searchAdvertisement(Request $request)
    {
        $data = Advertisement::getAdvertisementPublished()->getAdvertisementsSearch($request->search);
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function searchUsers(Request $request)
    {
        $data = User::where('name', 'like', '%' . $request->search . '%')->where('deleted', 0)->whereNotIn('role_id', [1, 2])->select('id', 'code', 'name')->orderBy('name', 'ASC')->get();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function searchUnion(Request $request)
    {
        $data = Union::where('name', 'like', '%' . $request->search . '%')->select('id', 'code', 'name')->orderBy('name', 'ASC')->get();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }
    public function searchGroup(Request $request)
    {
        $data = Group::where('name', 'like', '%' . $request->search . '%')->select('id', 'code', 'name')->orderBy('name', 'ASC')->get();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }
    public function searchChurch(Request $request)
    {
        $data = Church::where('name', 'like', '%' . $request->search . '%')->select('id', 'code', 'name')->orderBy('name', 'ASC')->get();
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }
}
