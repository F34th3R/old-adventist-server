<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\HeaderHelper;

use App\Union;
use Illuminate\Http\Request;

class UnionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Union::with(array('user' => function($query) {
            $query->select('id', 'email');
        }))->orderBy('id', 'DESC')->get();
        return response()->json([
            "data" => $data,
        ], 200);
    }

    public function nameAndCode()
    {
        $data = Union::select('id', 'name', 'user_id')->orderBy('name', 'ASC')->get();

        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }
    
    public function nameAndCodeByUser($user_id)
    {
        $data = Union::select('id', 'name', 'user_id')->where('user_id', $user_id)->orderBy('name', 'ASC')->get();

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
     * @param  \App\Union  $union
     * @return \Illuminate\Http\Response
     */
    public function show(Union $union)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Union  $union
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Union $union)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Union  $union
     * @return \Illuminate\Http\Response
     */
    public function destroy(Union $union)
    {
        //
    }
}
