<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\HeaderHelper;

use App\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
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
        $data = Group::select('id', 'name', 'user_id')->orderBy('name', 'ASC')->get();

        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }
    
    public function nameAndCodeByUser($user_id)
    {
        $data = Group::select('id', 'name', 'user_id')->where('user_id', $user_id)->orderBy('name', 'ASC')->get();

        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function nameAndCodeByUnion($union_id)
    {
        $data = Group::select('id', 'name', 'user_id')->where('union_id', $union_id)->orderBy('name', 'ASC')->get();

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
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
    }
}
