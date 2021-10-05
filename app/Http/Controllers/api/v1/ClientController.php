<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\api\v1\Client;
use Str;

class ClientController extends Controller
{
    // Get all Client
    public function index()
    {
        $return = [];
        $clients = Client::with('categories')->get();
        foreach ($clients as $client) {
            $client->category_ids = $client->categories->pluck('id');
            $return[] = $client;
        }
        return response()->json($return, 200);
    }

    public function store(Request $request)
    {
    	$request->validate([
    		'name' => 'required|unique:clients'
    	]);

    	$client = Client::create([
    		'slug' => Str::slug($request->name),
    		'name' => $request->name,
    		'name_ar' => $request->name_ar,
            'com_reg' => $request->com_reg,
            'email' => $request->email,
            'person' => $request->person
    	]);

        if($request->category) {
            $category = $request->category;
            if($category[0] == 0) unset($category[0]);
            $client->categories()->sync($category);
        }

    	return response()->json($client, 200);
    }

    public function update(Request $request, $id)
    {
    	$client = Client::findOrFail($id);

    	if($client->name != $request->name) {
	    	$request->validate([
	    		'name' => 'required|unique:clients'
	    	]);
	    	$client->slug = Str::slug($request->name);
    		$client->name = $request->name;
	    }
    	
    	$client->name_ar = $request->name_ar;
        $client->com_reg = $request->com_reg;
        $client->email = $request->email;
        $client->person = $request->person;

    	if($client->save()) {
            if($request->category) {
                $category = $request->category;
                if($category[0] == 0) unset($category[0]);
                $client->categories()->sync($category);
            }
    		return $this->index();
    	}

    	return response()->json(['error' => 'Unknown Error'], 500);
    }
}
