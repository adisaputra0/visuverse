<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Photo;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function index($slug){
        $photo = Photo::where("slug", $slug)->first();
        if(!$photo){
            return response()->json([
                "message" => "Photo not found"
            ], 404);
        }
        $like = false;
        if(Like::where("user_id", auth()->user()->id)->where("photo_id", $photo->id)->first()){
            $like = true;
        }
        return response()->json([
            "message" => "Get like success",
            "like" => $like,
        ]);
    }
    public function store($slug){
        $photo = Photo::where("slug", $slug)->first();
        if(!$photo){
            return response()->json([
                "message" => "Photo not found"
            ], 404);
        }
        if($photo->user_id == auth()->user()->id){
            return response()->json([
                "message" => "You are can't like your photo"
            ],422);
        }
        if(Like::where("user_id", auth()->user()->id)->where("photo_id", $photo->id)->first()){
            return response()->json([
                "message" => "You already like this photo"
            ],422);
        }
        Like::create([
            "photo_id" => $photo->id,
            "user_id" => auth()->user()->id
        ]);
        return response()->json([
            "message" => "Like success"
        ]);
    }
    public function destroy($slug){
        $photo = Photo::where("slug", $slug)->first();
        if(!$photo){
            return response()->json([
                "message" => "Photo not found"
            ], 404);
        }
        $like = Like::where("photo_id", $photo->id)->where("user_id", auth()->user()->id)->first();
        if($like){
            $like->delete();
        }
        return response()->json([
            "message" => "Unlike success"
        ]);
    }
}
