<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\api\v1\Car;
use Str;

class CarController extends Controller
{
    // Get all Car
    public function index()
    {
    	$cars = Car::with('distribution')->get();
    	return response()->json($cars, 200);
    }

    public function store(Request $request)
    {
    	$request->validate([
    		'code' => 'required|unique:cars',
    		'distribution_id' => 'required'
    	]);

    	$car = Car::create([
    		'code' => $request->code,
    		'distribution_id' => $request->distribution_id
    	]);

    	$car->distribution->name = $car->distribution->name;

    	return response()->json($car, 200);
    }

    public function update(Request $request, $id)
    {
    	$car = Car::findOrFail($id);

    	if($car->name != $request->name) {
	    	$request->validate([
	    		'code' => 'required|unique:cars'
	    	]);
    		$car->code = $request->code;
	    }
	    $request->validate([
    		'distribution_id' => 'required'
    	]);

    	
    	$car->distribution_id = $request->distribution_id;

    	if($car->save()) {
    		$cars = Car::with('distribution')->get();
    		return response()->json($cars, 200);
    	}

    	return response()->json(['error' => 'Unknown Error'], 500);
    }
}
