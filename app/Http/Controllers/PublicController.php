<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\Http\Controllers\Helpers\HeaderHelper;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function getAdvertisements()
    {
        $data = Advertisement::where('published', '1')->getAdvertisementsAll('id', 'DESC');
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }
}
