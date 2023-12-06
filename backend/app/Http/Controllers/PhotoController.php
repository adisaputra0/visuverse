<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    //Guest or users
    public function index(){
        return response()->json([
            "message" => "Success get photos",
            "photos" => Photo::all(),
        ]);
    }
    public function detail($slug){
        $photo = Photo::where("slug", $slug)->first();
        if(!$photo){
            return response()->json([
                "message" => "Photo not found",
            ], 404);
        }
        $comments = Comment::where("photo_id", $photo->id)->get();
        return response()->json([
            "message" => "Success get detail photos",
            "photos" => $photo,
            "comments" => $comments,
            "total_comment" => count($comments),
        ]);
    }

    //Users
    public function user_photos(){
        return response()->json([
            "message" => "Success get photos user",
            "photos" => Photo::where("user_id", auth()->user()->id)->get(),
        ]);
    }
    public function user_photos_detail($slug){
        $photo = Photo::where("slug", $slug)->first();
        if(!$photo){
            return response()->json([
                "message" => "Photo not found"
            ], 404);
        }
        if($photo->user_id != auth()->user()->id){
            return response()->json([
                "message" => "Forbidden access",
            ], 401);
        }
        return response()->json([
            "message" => "Success get detail photos",
            "photos" => $photo,
        ]);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "image" => "required|image|mimes:jpeg,png,jpg,gif",
            "description" => "required"
        ]);
        if($validator->fails()){
            return response()->json([
                "message" => "Invalid Field",
                "errors" => $validator->errors(),
            ], 403);
        }
        
        $slug = uniqid();
        $name_image = $slug . "." . $request->image->extension();
        $request->image->move("photos/", $name_image);

        Photo::create(array_merge($request->all(), [
            "user_id" => auth()->user()->id,
            "name_image" => $name_image,
            "slug" => $slug,
        ]));
        return response()->json([
            "message" => "Create photo success"
        ], 201);
    }
    public function update($id, Request $request){
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "image" => "image|mimes:jpeg,png,jpg,gif",
            "description" => "required"
        ]);
        if($validator->fails()){
            return response()->json([
                "message" => "Invalid Field",
                "errors" => $validator->errors(),
            ], 403);
        }
        $photo = Photo::find($id);
        if(!$photo){
            return response()->json([
                "message" => "Photo not found"
            ], 404);
        }

        if($photo->user_id != auth()->user()->id){
            return response()->json([
                "message" => "Forbidden access" 
            ], 401);
        }
        
        if($request->image){
            $file_path = public_path("photos/" . $photo->name_image);
            if(File::exists($file_path)){
                File::delete($file_path);
            }            
            $slug = uniqid();
            $name_image = $slug . "." . $request->image->extension();
            $request->image->move("photos/", $name_image);
            $request["name_image"] = $name_image;
        }

        $photo->update($request->all());
        return response()->json([
            "message" => "Update photo success"
        ]);
    }
    public function destroy($id){
        $photo = Photo::find($id);
        if(!$photo){
            return response()->json([
                "message" => "Photo not found"
            ], 404);
        }

        if($photo->user_id != auth()->user()->id){
            return response()->json([
                "message" => "Forbidden access",
            ], 401);
        }

        $photo->delete();
        return response()->json([
            "message" => "Delete photo success"
        ]);
    }
}
