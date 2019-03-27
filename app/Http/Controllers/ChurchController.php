<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\HeaderHelper;

use App\Church;
use Illuminate\Http\Request;

class ChurchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Church  $church
     * @return \Illuminate\Http\Response
     */
    public function show(Church $church)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Church  $church
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Church $church)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Church  $church
     * @return \Illuminate\Http\Response
     */
    public function destroy(Church $church)
    {
        //
    }
}
