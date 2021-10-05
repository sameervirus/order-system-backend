<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\api\v1\Product;
use Str;

class ProductController extends Controller
{
    // Get all Category
    public function index()
    {
    	$products = Product::with('category')->get();
        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
    	
        $request->validate([
    		'name' => 'required|unique:products',
            'pool_code' => 'required',
            'code' => 'required|unique:products',
            'category_id' => 'required',
            'price' => 'required',
            'vat' => 'required'
    	]);

    	$product = Product::create([
    		'slug' => Str::slug($request->name),
    		'name' => $request->name,
    		'name_ar' => $request->name_ar,
            'pool_code' => $request->pool_code,
            'code' => $request->code,
            'category_id' => $request->category_id,
            'client_code' => $request->client_code ?? '',
            'price' => $request->price,
            'vat' => $request->vat
    	]);

        $product->category->name = $product->category->name;

    	return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
    	$product = Product::findOrFail($id);

        $request->validate([
            'pool_code' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            'vat' => 'required'
        ]);

    	if($product->name != $request->name) {
	    	$request->validate([
	    		'name' => 'required|unique:categories'
	    	]);
            $product->slug = Str::slug($request->name);
            $product->name = $request->name;
	    } 

        if($product->code != $request->code) {
            $request->validate([
                'code' => 'required|unique:categories'
            ]);
            $product->code = $request->code;
        }
    	
    	$product->name_ar = $request->name_ar;
        $product->pool_code = $request->pool_code;
        $product->category_id = $request->category_id;
        $product->client_code = $request->client_code ?? '';
        $product->price = $request->price;
        $product->vat = $request->vat;

    	if($product->save()) {
            $products = Product::with('category')->get();
    		return response()->json($products, 200);
    	}

    	return response()->json(['error' => 'Unknown Error'], 500);
    }
}
