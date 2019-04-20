<?php

namespace App\Http\Controllers\Helpers;

use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class storeAdvertisementImageHelper
{
    public static function store(Request $request)
    {
        $department = Department::select('code')->where('id', $request->department_id)->get();
        $imageName = $request->parent_code.'_'.$department[0]->code.'__'.'f34th3r.io_'.str_random(30);

        $exploded = explode(',', $request->image);
        $decode = base64_decode($exploded[1]);

        $extension = str_contains($exploded[0], 'jpeg') ? 'jpg' : 'png';
        $fileName = $imageName.'.'.$extension;

        Storage::disk('ad_img')->put($fileName, $decode);

        return [
            'imageName' => $imageName,
            'fileName' => $fileName
        ];
    }
}
