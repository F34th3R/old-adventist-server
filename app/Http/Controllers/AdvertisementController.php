<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\AdvertisementHelper as Helper;
use App\Http\Controllers\Helpers\CodeGenerator;
use App\Http\Controllers\Helpers\HeaderHelper;

use App\Advertisement;
use App\Department;
use App\Http\Controllers\Helpers\storeAdvertisementImageHelper;
use App\Image;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
//        try {
//            $users = User::where([
//                'id' => Auth::user()->id,
//                'deleted' => 0
//            ])->whereNotIn('role_id', [1, 2])->select('id','code')->get();
//            if ($users[0]->code) {
//                $data = Advertisement::where([
//                    'published' => 1,
//                    'parent_code' => Auth::user()->code
//                ])->getAdvertisementsAll('id', 'DESC');
//            } else {
//                $data = Advertisement::getAdvertisementPublished()->getAdvertisementsAll('id', 'DESC');
//            }
//        } catch (\Exception $e) {
//            return response()->json([
//                "data" => [],
//            ], 200, HeaderHelper::$header);
//        }
        return response()->json([
            "data" => Advertisement::getAdvertisementPublished()->getAdvertisementsAll('id', 'DESC'),
        ], 200, HeaderHelper::$header);
    }
    
    public function getAdvertisements()
    {
        try {
            if (Auth::user()->role_id == 1) {
                $data = Advertisement::getAdvertisementsAll('id', 'DESC');
            } else {
                $helper = new Helper();
                $department_id = [];

                $user_department = Department::where('user_id', Auth::user()->id)->select('id')->get();
                $department_id = $helper->departmentArray($department_id, $user_department);

                $data = Advertisement::getAdvertisementMold($department_id, 'id', 'DESC');

//              $data = Advertisement::getAdvertisementsAll('id', 'DESC')->where('id', $request->id);
            }
        } catch (\Exception $e) {
            return response()->json([
                "data" => false,
            ], 200, HeaderHelper::$header);
        }
        return response()->json([
            "data" => $data,
        ], 200, HeaderHelper::$header);
    }

    public function filter()
    {
        $department_id = [];
        $helper = new Helper();

        //? select all the ids where user_id is equals to the input (output = [])
        $user_department = Department::select('id')->where('user_id', Auth::user()->id)->get();
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
                'description' => 'required|min:5',
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

    public function show(Request $request)
    {
        try {
            $data = Advertisement::with(array('department' => function($q) {
                $q->select('id', 'name', 'user_id')->with(array('user' => function($user) {
                    $user->select('id', 'code', 'name', 'deleted');
                }));
            }))->with(array('image' => function($q) {
                $q->select('id', 'path');
            }))->where('id', $request->id)->get();
            if ($data[0]->department->user->deleted != 0) {
                $data = [];
            }
        } catch (\Exception $e) {
            return response()->json([
                "data" => [],
            ], 200);
        }
        return response()->json([
            "data" => $data,
        ], 200);
    }

    public function test(Request $request, Advertisement $id)
    {
        dd($request->getContent());
    }

    public function update(Request $request, Advertisement $id)
    {
//        dd($request);
        $this->validate($request, [
            'title' => 'required|min:5',
            'description' => 'required|min:5',
            'fragment' => 'required|min:5|max:80',
            'department_id' => 'required|numeric',
        ]);
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
                Storage::disk('ad_img')->delete($request->path);

                // Save new Image
                $imageData = storeAdvertisementImageHelper::store($request);
                Image::where('path', $request->path)->update([
                    'name' => $imageData['imageName'],
                    'path' => $imageData['fileName'],
                ]);
                $image = Image::where('path', $imageData['fileName'])->select('id')->get();
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

    public function destroy($id)
    {
        try {
            $image_id = Advertisement::where('id', $id)->select('image_id')->get();
            $image_path = Image::where('id', $image_id[0]->image_id)->get();
            Storage::disk('ad_img')->delete($image_path[0]->path);
            Image::where('id', $image_id[0]->image_id)->delete();
            $ad = Advertisement::where('id', $id);
            $ad->delete();
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
