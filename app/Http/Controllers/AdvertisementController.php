<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\AdvertisementHelper as Helper;
use App\Http\Controllers\Helpers\HeaderHelper;

use App\Advertisement;
use App\Department;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Advertisement::getAdvertisementsAll('id', 'DESC');

        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function filter($user_id)
    {
        $department_id = [];
        $helper = new Helper();

        //? select all the ids where user_id is equals to the input (output = [])
        $user_department = Department::select('id')->where('user_id', $user_id)->get();
        //? 
        $department_id = $helper->departmentArray($department_id, $user_department);

        $advertisement = Advertisement::getAdvertisementMold($department_id, 'id', 'DESC');

        return response()->json([
            "data" => $advertisement
        ], 200, HeaderHelper::$header);
    }

    public function searchAdvertisements(Request $request)
    {
        $department_id = [];
        $helper = new Helper();

        switch ($request->type)
        {
            case "union": 
                $union_user_id = Union::getUnionUserId($request->id);
                $union_department = $helper->getUnionDepartmentId('user_id', $union_user_id[0]->user_id);

                $department_id = $helper->departmentArray($department_id, $union_department );

                $advertisement = Advertisement::getAdvertisementPublished()->getAdvertisementModel($department_id);

                return response()->json([
                    "data" => $advertisement,
                ], 200);
        }
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
     * @param  \App\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function show(Advertisement $advertisement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Advertisement $advertisement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertisement $advertisement)
    {
        //
    }
}
