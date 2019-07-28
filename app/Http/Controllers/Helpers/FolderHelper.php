<?php


namespace App\Http\Controllers\Helpers;


use App\Church;
use App\Group;
use App\Union;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FolderHelper
{
    private static function imageStorage($path, $decode, $extension)
    {
        $uniqueName = 'fth_'.Auth::user()->code;
        $imagePath = $path.$uniqueName.Str::random(20).'.'.$extension;
        Storage::disk('advertisement_image')->put($imagePath, $decode);
        return $imagePath;
    }

    public static function createDelete($code, $create = true, $image = false, $decodeOrName = null, $extension = null)
    {
        $user_code = Auth::user()->code;
        if ($user_code[0] == 'U') {

            $path = $user_code.'/'.$code;
            if ($create) {
                Storage::disk('advertisement_image')->makeDirectory($path);
                if ($image) {
                    return self::imageStorage($path.'/', $decodeOrName, $extension);
                }
            } else {
                Storage::disk('advertisement_image')->deleteDirectory($path);
                if ($image) {
                    Storage::disk('advertisement_image')->delete($path);
                }
            }

        } elseif ($user_code[0] == 'G') {
            $group = Group::select('id', 'union_id')->where('code', $user_code)->first();
            $union = Union::select('id', 'code')->where('id', $group->union_id)->first();

            $path = $union->code.'/'.Auth::user()->code.'/'.$code;
            if ($create) {
                Storage::disk('advertisement_image')->makeDirectory($path);
                if ($image) {
                    return self::imageStorage($path.'/', $decodeOrName, $extension);
                }
            } else {
                Storage::disk('advertisement_image')->deleteDirectory($path);
                if ($image) {
                    Storage::disk('advertisement_image')->delete($path);
                }
            }

        } elseif ($user_code[0] == 'C') {
            $church = Church::select('id', 'group_id')->where('code', $user_code)->first();
            $group = Group::select('id', 'union_id', 'code')->where('id', $church->group_id)->first();
            $union = Union::select('id', 'code')->where('id', $group->union_id)->first();

            $path = $union->code.'/'.$group->code.'/'.Auth::user()->code.'/'.$code;
            if ($create) {
                Storage::disk('advertisement_image')->makeDirectory($path);
                if ($image) {
                    return self::imageStorage($path.'/', $decodeOrName, $extension);
                }
            } else {
                Storage::disk('advertisement_image')->deleteDirectory($path);
                if ($image) {
                    Storage::disk('advertisement_image')->delete($path);
                }
            }
        }
        return null;
    }
}
