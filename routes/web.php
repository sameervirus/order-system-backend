<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact dev@egyptbakry.com'], 404);
});
Route::get('/', function () {
	return date('H:i:s');
	$h= 2;
	if (date('H') > 8) {
		return $h;
		$h = $h + 1;    	
    }
    return date('H');
    $due_date = today()->addDays($h);
    return $due_date;
});
use App\Http\Controllers\api\v1\OrderController;
Route::get('/orders', [OrderController::class, 'getProductionOrders']);