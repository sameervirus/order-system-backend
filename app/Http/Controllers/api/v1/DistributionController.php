<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\api\v1\Distribution;
use Str;

class DistributionController extends Controller
{
    // Get all Distribution
    public function index()
    {
    	$distribution = Distribution::with('district')->get();
        return response()->json($distribution, 200);
    }

    public function store(Request $request)
    {
    	$request->validate([
    		'name' => 'required|unique:distributions',
            'code' => 'required|unique:distributions'
    	]);

    	$distribution = Distribution::create([
    		'slug' => Str::slug($request->name),
    		'name' => $request->name,
    		'name_ar' => $request->name_ar,
            'code' => $request->code,
            'district_id' => $request->district_id
    	]);

    	return response()->json($distribution, 200);
    }

    public function update(Request $request, $id)
    {
    	$distribution = Distribution::findOrFail($id);

    	if($distribution->name != $request->name) {
	    	$request->validate([
	    		'name' => 'required|unique:distributions'
	    	]);
            $distribution->slug = Str::slug($request->name);
            $distribution->name = $request->name;
	    }    

        if($distribution->code != $request->code) {
            $request->validate([
                'code' => 'required|unique:distributions'
            ]);
            $distribution->code = $request->code;
        }
    	
    	$distribution->district_id = $request->district_id;
        $distribution->name_ar = $request->name_ar;

    	if($distribution->save()) {
    		return response()->json(Distribution::all(), 200);
    	}

    	return response()->json(['error' => 'Unknown Error'], 500);
    }
}
