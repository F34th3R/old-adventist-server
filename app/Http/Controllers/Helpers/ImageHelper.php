<?php

namespace App\Http\Controllers\Helpers;

use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    public static function store($image, $department_id)
    {
        $departmentCode = Department::select('code')->where('id', $department_id)->get();
        $exploded = explode(',', $image);
        $decode = base64_decode($exploded[1]);

        $extension = Str::contains($exploded[0], 'jpeg') ? 'jpg' : 'png';
        $imageCreate = FolderHelper::createDelete($departmentCode[0]->code, true,true, $decode, $extension);
        $uniqueName = 'fth_'.Auth::user()->code;
        return [
            'name' => $uniqueName.explode('/'.$uniqueName, $imageCreate)[1],
            'path' => $imageCreate
        ];
    }
}
