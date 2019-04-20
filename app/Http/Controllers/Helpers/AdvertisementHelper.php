<?php
/**
 * Created by F34th3r.io
 */

namespace App\Http\Controllers\Helpers;

class AdvertisementHelper {

    //? This function What does it do? 
    //* $arrayDepartment receive the empty array
    //* $department_belongs receive all the departments where user_id is the input of the request 
    //* the output is the full array 
    public function departmentArray($arrayDepartment, $department_belongs)
    {
        for ($i = 0; $i < count($department_belongs); $i ++) {
            array_push($arrayDepartment, $department_belongs[$i]->id);
        }
        return $arrayDepartment;
    }

}