<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\api\v1\Branch;
use Str;

class BranchController extends Controller
{
    public function index()
    {
    	$branches = Branch::with('client')
    						->with('city')
    						->with('district')
    						->with('distribution')
    						->get();
    	return response()->json($branches, 200);
    }

    public function store(Request $request)
    {
    	$request->validate([
    		'name' => 'required|unique:branches',
	        'due_period' => 'required',
			'code' => 'required|unique:branches',
            'vat' => 'required',
            'opening' => 'required',
            'closing' => 'required',
            'close_time' => 'required',
			'client_id' => 'required',
			'city_id' => 'required',
			'district_id' => 'required',
			'distribution_id' => 'required'
    	]);

    	$branch = Branch::create([
    		'slug' => Str::slug($request->name),
    		'name' => $request->name,
    		'name_ar' => $request->name_ar,
	        'code' => $request->code,
            'vat' => $request->vat,
            'opening' => $request->opening,
            'closing' => $request->closing,
            'due_period' => $request->due_period,
			'close_time' => $request->close_time,
			'client_id' => $request->client_id,
			'city_id' => $request->city_id,
			'district_id' => $request->district_id,
			'distribution_id' => $request->distribution_id
    	]);

    	$branch->client->name = $branch->client->name;
    	$branch->city->name = $branch->city->name;
    	$branch->district->name = $branch->district->name;
    	$branch->distribution->name = $branch->distribution->name;

    	return response()->json($branch, 200);
    }

    public function update(Request $request, $id)
    {
    	$branch = Branch::findOrFail($id);

    	if($branch->name != $request->name) {
	    	$request->validate([
	    		'name' => 'required|unique:branches'
	    	]);
	    	$branch->slug = Str::slug($request->name);
    		$branch->name = $request->name;
	    }

        if($branch->code != $request->code) {
            $request->validate([
                'code' => 'required|unique:branches'
            ]);
            $branch->code = $request->code;
        }

	    $request->validate([
	        'vat' => 'required',
            'opening' => 'required',
            'closing' => 'required',
            'due_period' => 'required',
			'close_time' => 'required',
			'client_id' => 'required',
			'city_id' => 'required',
			'district_id' => 'required',
			'distribution_id' => 'required'
    	]);
    	
    	$branch->name_ar = $request->name_ar;
    	$branch->vat = $request->vat;
        $branch->opening = $request->opening;
        $branch->closing = $request->closing;
        $branch->due_period = $request->due_period;
    	$branch->close_time = $request->close_time;
    	$branch->client_id = $request->client_id;
    	$branch->city_id = $request->city_id;
    	$branch->district_id = $request->district_id;
    	$branch->distribution_id = $request->distribution_id;

    	if($branch->save()) {
    		$branches = Branch::with('client')
    						->with('city')
    						->with('district')
    						->with('distribution')
    						->get();
    		return response()->json($branches, 200);
    	}

    	return response()->json(['error' => 'Unknown Error'], 500);
    }
}
