<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\api\v1\District;
use Str;

class DistrictController extends Controller
{
    // Get all District
    public function index()
    {
    	$districts = District::with('city')->get();
    	return response()->json($districts, 200);
    }

    public function store(Request $request)
    {
    	$request->validate([
    		'name' => 'required|unique:districts',
            'code' => 'required|unique:districts',
    		'city_id' => 'required'
    	]);

    	$district = District::create([
    		'slug' => Str::slug($request->name),
    		'name' => $request->name,
            'code' => $request->code,
    		'city_id' => $request->city_id,
    		'name_ar' => $request->name_ar
    	]);

    	$district->city->name = $district->city->name;

    	return response()->json($district, 200);
    }

    public function update(Request $request, $id)
    {
    	$district = District::findOrFail($id);

    	if($district->name != $request->name) {
	    	$request->validate([
	    		'name' => 'required|unique:districts'
	    	]);
	    	$district->slug = Str::slug($request->name);
    		$district->name = $request->name;
	    }

        if($district->code != $request->code) {
            $request->validate([
                'code' => 'required|unique:districts'
            ]);
            $district->code = $request->code;
        }

	    $request->validate([
    		'city_id' => 'required'
    	]);

    	
    	$district->name_ar = $request->name_ar;
    	$district->city_id = $request->city_id;

    	if($district->save()) {
    		$districts = District::with('city')->get();
    		return response()->json($districts, 200);
    	}

    	return response()->json(['error' => 'Unknown Error'], 500);
    }
}
