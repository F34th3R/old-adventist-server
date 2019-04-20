<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\AdvertisementHelper as Helper;
use App\Http\Controllers\Helpers\CodeGenerator;
use App\Http\Controllers\Helpers\HeaderHelper;

use App\Advertisement;
use App\Department;
use App\Http\Controllers\Helpers\storeAdvertisementImageHelper;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
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

    public function store(Request $request)
    {
        $codeGenerator = new CodeGenerator();
        try {
            $this->validate($request, [
                'title' => 'required|min:5',
                'description' => 'required|min:5|max:1000',
                'fragment' => 'required|min:5|max:80',
                'department_id' => 'required|numeric',
                'parent_code' => 'required',
            ]);

            $imageData = storeAdvertisementImageHelper::store($request);
            $image = Image::create([
                'name' => $imageData['imageName'],
                'path' => $imageData['fileName'],
                'tag' => 'ad-image'
            ]);
            Advertisement::create([
                'code' => $codeGenerator->generator('ADVERTISEMENTS'),
                'title' => $request->title,
                'parent_code' => $request->parent_code,
                'department_id' => $request->department_id,
                'publicationDate' => $request->publicationDate,
                'eventDate' => $request->eventDate,
                'fragment' => $request->fragment,
                'description' => $request->description,
                'published' => $request->published,
                'image_id' => $image->id,
                'time' => $request->time,
                'place' => $request->place,
                'guest' => $request->guest,
            ]);
        }
        catch (\Exception $e) {
            $img = Image::find($image->id);
            $img->delete();
            Storage::disk('ad_img')->delete($imageData['fileName']);
            return response()->json([
                'response' => false,
            ],404);
        }
        return response()->json([
            "response" => true
        ], 200);
    }

    public function show(Advertisement $id)
    {
        $data = Advertisement::with(array('department' => function($q) {
            $q->select('id', 'name');
        }))->with(array('image' => function($q) {
            $q->select('id', 'path');
        }))->where('id', $id)->get();
        return response()->json([
            "data" => $data,
        ], 200);
    }

    public function update(Request $request, Advertisement $id)
    {
        $this->validate($request, [
            'title' => 'required|min:5',
            'description' => 'required|min:5|max:1000',
            'fragment' => 'required|min:5|max:80',
            'department_id' => 'required|numeric',
        ]);

        $department = Department::with('user')->where('id', $request->department_id)->get();
        $department_name = strtolower(preg_replace('/\s+/', '-', $department[0]->name));
        $user_name = strtolower(preg_replace('/\s+/', '-', $department[0]->user->name));
        $imageName = $department[0]->user->id.'__'.'f34th3r_'.str_random(30).'fth_jft'.$user_name.'_'.$department_name;

        try {
            if ($request->image_charge) {
                $id->update([
                    'title' => $request->title,
                    'department_id' => $request->department_id,
                    'publicationDate' => $request->publicationDate,
                    'eventDate' => $request->eventDate,
                    'fragment' => $request->fragment,
                    'description' => $request->description,
                    'published' => $request->published,
                    'time' => $request->time,
                    'place' => $request->place,
                    'guest' => $request->guest,
                ]);
            }
            else {
                // Delete old image
                // TODO
//                Storage::delete('public/images/'.$request->path);


                // Save new Image
                $exploded = explode(',', $request->image);
                $decode = base64_decode($exploded[1]);
                if (str_contains($exploded[0], 'jpeg')) { $extension = 'jpg'; }
                else { $extension = 'png'; }
                $fileName = $imageName.'_'.str_random().'.'.$extension;
                $path = public_path('images/').$fileName;
                file_put_contents($path, $decode);

                Image::where('path', $request->path)->update([
                    'name' => $imageName,
                    'path' => $fileName
                ]);
                $image = Image::where('path', $fileName)->select('id')->get();
                $id->update([
                    'title' => $request->title,
                    'department_id' => $request->department_id,
                    'publicationDate' => $request->publicationDate,
                    'eventDate' => $request->eventDate,
                    'fragment' => $request->fragment,
                    'description' => $request->description,
                    'published' => $request->published,
                    'image_id' => $image[0]->id,
                    'time' => $request->time,
                    'place' => $request->place,
                    'guest' => $request->guest,
                ]);
            }
            return response()->json([
                "response" => true,
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                "response" => false,
            ], 404);
        }
    }

    public function destroy(Advertisement $id)
    {
        dd($id->image());
        try {
            Storage::disk('ad_img')->delete('CHEf9l_DIlLTM__f34th3r.io_9sLgq9vbY6GtK4nABcRjoUGFaBp9Xf.jpg');
            $id->delete();
        }
        catch (\Exception $e) {
            return response()->json([
                'response' => false,
            ],404);
        }
        return response()->json([
            "response" => true,
        ], 200);
    }
}
