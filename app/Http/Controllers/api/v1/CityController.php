<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\api\v1\City;
use Str;

class CityController extends Controller
{
    // Get all City
    public function index()
    {
    	return response()->json(City::all(), 200);
    }

    public function store(Request $request)
    {
    	$request->validate([
    		'name' => 'required|unique:cities'
    	]);

    	$city = City::create([
    		'slug' => Str::slug($request->name),
    		'name' => $request->name,
    		'name_ar' => $request->name_ar
    	]);

    	return response()->json($city, 200);
    }

    public function update(Request $request, $id)
    {
    	$city = City::findOrFail($id);

    	if($city->name != $request->name) {
	    	$request->validate([
	    		'name' => 'required|unique:cities'
	    	]);
	    }    	

    	$city->slug = Str::slug($request->name);
    	$city->name = $request->name;
    	$city->name_ar = $request->name_ar;

    	if($city->save()) {
    		return response()->json(City::all(), 200);
    	}

    	return response()->json(['error' => 'Unknown Error'], 500);
    }
}
