<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index($category_name){
        $category = Category::where("category_name", $category_name)->first();
        if(!$category){
            return response()->json([
                "message" => "Category not found"
            ], 404);
        }
        $photos = [];
        foreach(Photo::where("categories_id", $category->id)->get() as $photo){
            $photos[] = $photo;
        }
        return response()->json([
            "message" => "Success get category",
            "category" => $category,
            "photos" => $photos,
        ]);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            "category_name" => "required|unique:categories"
        ]);
        if($validator->fails()){
            return response()->json([
                "message" => "Invalid Field",
                "errors" => $validator->errors(),
            ], 403);
        }
        Category::create([
            "category_name" => $request->category_name,
            "user_id" => auth()->user()->id,
        ]);
        return response()->json([
            "message" => "Create category success",
        ],201);
    }
    public function destroy($category_name){
        $category = Category::where("category_name", $category_name)->first();
        if(!$category){
            return response()->json([
                "message" => "Category not found"
            ], 404);
        }
        if($category->user_id != auth()->user()->id){
            return response()->json([
                "message" => "Forbidden access"
            ], 401);
        }
        $category->delete();
        return response()->json([
            "message" => "Delete category success"
        ]);
    }
}
