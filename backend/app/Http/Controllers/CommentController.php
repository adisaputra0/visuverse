<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Photo;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index($slug){
        $photo = Photo::where("slug", $slug)->first();
        if(!$photo){
            return response()->json([
                "message" => "Photo not found"
            ], 404);
        }
        $comments = Comment::where("photo_id", $photo->id)->get();
        foreach($comments as $comment){
            $comment->user = User::find($comment->user_id);
        }
        return response()->json([
            "message" => "Get comments success",
            "comments" => $comments,
        ],201);
    }
    public function store($slug, Request $request){
        $validator = Validator::make($request->all(), [
            "comment_text" => "required",
        ]);
        if($validator->fails()){
            return response()->json([
                "message" => "Invalid Field",
                "errors" => $validator->errors(),
            ], 403);
        }
        $photo = Photo::where("slug", $slug)->first();
        if(!$photo){
            return response()->json([
                "message" => "Photo not found"
            ], 404);
        }
        $comment = Comment::create([
            "photo_id" => $photo->id,
            "user_id" => auth()->user()->id,
            "comment_text" => $request->comment_text,
        ]);
        return response()->json([
            "message" => "Create comment success",
        ],201);
    }
    public function detail($slug, $comment_id){
        $photo = Photo::where("slug", $slug)->first();
        if(!$photo){
            return response()->json([
                "message" => "Photo not found"
            ], 404);
        }
        $comment = Comment::find($comment_id);
        if(!$comment){
            return response()->json([
                "message" => "Comment not found"
            ], 404);
        }
        if($comment->user_id != auth()->user()->id){
            return response()->json([
                "message" => "Forbidden access",
            ], 401);
        }
        return response()->json([
            "message" => "Succes get detail comment",
            "comment" => $comment->comment_text,
        ]);
    }
    public function update($slug, $comment_id, Request $request){
        $validator = Validator::make($request->all(), [
            "comment_text" => "required",
        ]);
        if($validator->fails()){
            return response()->json([
                "message" => "Invalid Field",
                "errors" => $validator->errors(),
            ], 403);
        }
        $photo = Photo::where("slug", $slug)->first();
        if(!$photo){
            return response()->json([
                "message" => "Photo not found"
            ], 404);
        }
        $comment = Comment::find($comment_id);
        if(!$comment){
            return response()->json([
                "message" => "Comment not found"
            ], 404);
        }
        if($comment->user_id != auth()->user()->id){
            return response()->json([
                "message" => "Forbidden access",
            ], 401);
        }
        $comment->update([
            "comment_text" => $request->comment_text,
        ]);
        return response()->json([
            "message" => "Succes update comment",
        ]);
    }
    public function destroy($slug, $comment_id){
        $photo = Photo::where("slug", $slug)->first();
        if(!$photo){
            return response()->json([
                "message" => "Photo not found"
            ], 404);
        }
        $comment = Comment::find($comment_id);
        if(!$comment){
            return response()->json([
                "message" => "Comment not found"
            ], 404);
        }
        if($comment->user_id != auth()->user()->id){
            return response()->json([
                "message" => "Forbidden access",
            ], 401);
        }
        $comment->delete();
        return response()->json([
            "message" => "Succes delete comment",
        ]);
    }
}
