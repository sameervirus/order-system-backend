<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\api\v1\Category;
use Str;

class CategoryController extends Controller
{
    // Get all Category
    public function index()
    {
    	return response()->json(Category::all(), 200);
    }

    public function store(Request $request)
    {
    	$request->validate([
    		'name' => 'required|unique:categories',
            'code' => 'required|unique:categories'
    	]);

    	$category = Category::create([
    		'slug' => Str::slug($request->name),
    		'name' => $request->name,
    		'name_ar' => $request->name_ar,
            'code' => $request->code
    	]);

    	return response()->json($category, 200);
    }

    public function update(Request $request, $id)
    {
    	$category = Category::findOrFail($id);

    	if($category->name != $request->name) {
	    	$request->validate([
	    		'name' => 'required|unique:categories'
	    	]);
            $category->slug = Str::slug($request->name);
            $category->name = $request->name;
	    }  

        if($category->code != $request->code) {
            $request->validate([
                'code' => 'required|unique:categories'
            ]);
            $category->code = $request->code;
        }
    	
    	$category->name_ar = $request->name_ar;

    	if($category->save()) {
    		return response()->json(Category::all(), 200);
    	}

    	return response()->json(['error' => 'Unknown Error'], 500);
    }
}
