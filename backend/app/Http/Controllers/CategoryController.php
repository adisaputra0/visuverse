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
        $categories = [];
        $photos_select = [];
        foreach(Photo::all() as $photo){
            foreach(explode(",", $photo->categories_id) as $c){
                if($c == $category->id){
                    $photos_select[] = $photo;
                }
            }
        }
        foreach($photos_select as $photo){
            $photos[] = $photo;
            
            $category_photo = explode(",", $photo->categories_id);
            foreach($category_photo as $item_category){
                $category = Category::find($item_category);
                $categories[] = $category->category_name;
            }
            $photo->category_name = $categories;
            $categories = [];
        }
        return response()->json([
            "message" => "Success get category",
            "category" => $category,
            "photos" => $photos,
        ]);
    }
    public function all_categories(){
        $categories = Category::orderBy("id", "DESC")->get();
        foreach($categories as $category){
            $photo = Photo::orderBy("created_at", "DESC")->where("categories_id", $category->id)->first();

            if($photo){
                $category->thumbnail = $photo->name_image;
            }else{
                $category->thumbnail = null;
            }
        }
        return response()->json([
            "message" => "Success get all categories",
            "category" => $categories,
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
