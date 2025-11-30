<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    function add_category(){
        $categories = Category::all();
        return view('backend.category.category',[
            'categories'=>$categories
        ]);
    }
    function store_category(Request $request){
        $request->validate([
            'category_name'=>['required', 'unique:categories'],
            'category_image'=>'required',
        ]);

        $slug = strtolower(str_replace(' ','-',$request->category_name));

        $cat_image = $request->category_image;
        $extension = $cat_image->extension();
        $file_name = uniqid().'.'. $extension;

        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->category_image);
        $image->scale(width: 300, height:400);
        $image->save(public_path('uploads/category/'.$file_name));

        Category::insert([
            'category_name'=>$request->category_name,
            'category_image'=>$file_name,
            'category_slug'=>$slug,
        ]);

        return back()->with('success', 'Category Added Successfully');
    }
}
