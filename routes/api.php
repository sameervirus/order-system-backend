<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportController;
use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\ProductController;
use App\Http\Controllers\api\v1\DistributionController;
use App\Http\Controllers\api\v1\CityController;
use App\Http\Controllers\api\v1\DistrictController;
use App\Http\Controllers\api\v1\CarController;
use App\Http\Controllers\api\v1\ClientController;
use App\Http\Controllers\api\v1\BranchController;
use App\Http\Controllers\api\v1\OrderController;
use App\Http\Controllers\api\v1\HelperDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('invlaid', function ()
{
	return response()->json(['error' => 'UnAuthorised'], 401);
})->name('invlaid');
Route::post('login', [PassportController::class, 'login']);
Route::post('register', [PassportController::class, 'register']);
 
Route::middleware('auth:api')->group(function () {
    Route::get('user', [PassportController::class, 'details']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



// Coding Routes
Route::middleware('auth:api')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('distributions', DistributionController::class);
    Route::resource('cities', CityController::class);
    Route::resource('districts', DistrictController::class);
    Route::resource('cars', CarController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('branches', BranchController::class);
    Route::resource('orders', OrderController::class);
});

Route::middleware('auth:api')->group(function () {

    Route::get('client-product', [OrderController::class, 'clientProduct']);
    Route::get('helpers/foreign', [HelperDataController::class, 'foreign']);

    // Orders Review
    Route::get('orders-products', [OrderController::class, 'getProductionOrders']);
    Route::post('update-approved', [OrderController::class, 'updateApproved']);
});