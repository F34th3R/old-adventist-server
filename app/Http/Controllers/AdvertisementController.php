<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\IteratorHelper as Helper;
use App\Http\Controllers\Helpers\IteratorHelper;
use App\Http\Controllers\Helpers\GeneratorHelper;
use App\Http\Controllers\Helpers\HeaderHelper;

use App\Advertisement;
use App\Department;
use App\Http\Controllers\Helpers\ImageHelper;
use App\Image;
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
                $user_department = Department::where('user_id', Auth::user()->id)->select('id')->get();
                $data = Advertisement::getAdvertisementMold(IteratorHelper::iterator_Id($user_department), 'id', 'DESC');
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

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required|min:5',
                'description' => 'required|min:5',
                'fragment' => 'required|min:5|max:80',
                'department_id' => 'required|numeric',
                'parent_code' => 'required',
            ]);

            $imageData = ImageHelper::store($request->input('image'), $request->input('department_id'));
            $image = Image::create([
                'name' => $imageData['name'],
                'path' => $imageData['path'],
                'tag' => 'ad-image'
            ]);
            Advertisement::create([
                'code' => GeneratorHelper::code('ADVERTISEMENT'),
                'title' => $request->title,
                'parent_code' => $request->parent_code,
                'department_id' => $request->department_id,
                'publicationDate' => $request->publicationDate,
                'eventDate' => $request->eventDate,
                'fragment' => $request->fragment,
                'description' => $request->description,
                'published' => $request->published,
                'image_id' => $image->id,
                'time' => $request->time == 'feather_empty' ? '' : $request->time,
                'place' => $request->place == 'feather_empty' ? '' : $request->place,
                'guest' => $request->guest == 'feather_empty' ? '' : $request->guest,
            ]);
        }
        catch (\Exception $e) {
            $img = Image::find($image->id);
            $img->delete();
            Storage::disk('advertisement_image')->delete($imageData['path']);
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

    public function update(Request $request, Advertisement $id)
    {
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
                $imageData = ImageHelper::store($request->input('image'), $request->input('department_id'));
                Image::where('path', $request->path)->update([
                    'name' => $imageData['name'],
                    'path' => $imageData['path'],
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
            $image_id = Advertisement::select('image_id')->where('id', $id)->select('image_id')->get();
            $image_path = Image::select('path')->where('id', $image_id[0]->image_id)->get();
            Storage::disk('advertisement_image')->delete($image_path[0]->path);
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
