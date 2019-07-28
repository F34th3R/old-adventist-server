<?php
/**
 * Created by F34th3r.io
 */

namespace App\Http\Controllers\Helpers;

class IteratorHelper {

    //? This function What does it do? 
    //* $arrayDepartment receive the empty array
    //* $department_belongs receive all the departments where user_id is the input of the request 
    //* the output is the full array
    // IMPORTANT ius not in use deprecate
    public static function iterator_Department_id($department_belongs)
    {
        $result = [];
        for ($i = 0; $i < count($department_belongs); $i ++) {
            array_push($result, $department_belongs[$i]->id);
        }
        return $result;
    }

    public static function iterator_Id($array)
    {
        $result = [];
        for ($i = 0; $i < count($array); $i ++) {
            array_push($result, $array[$i]->id);
        }
        return $result;
    }

    public static function iterator_User_id($array)
    {
        $result = [];
        for ($i = 0; $i < count($array); $i ++) {
            array_push($result, $array[$i]->user_id);
        }
        return $result;
    }

}
