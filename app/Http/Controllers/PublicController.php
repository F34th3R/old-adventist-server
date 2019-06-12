<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\Http\Controllers\Helpers\HeaderHelper;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function getAdvertisements()
    {
//        $data = Advertisement::where('published', '1')->getAdvertisementsAll('id', 'DESC');
        $data = Advertisement::where('published', '1')->with(array('department' => function($q) {
            $q->select('id', 'name', 'user_id')->with(array('user' => function($user) {
                $user->select('id', 'code', 'name');
            }));
        }))->with(array('image' => function($q) {
            $q->select('id', 'path');
        }))->orderBy('id', 'DESC')->paginate(1);
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }
}
