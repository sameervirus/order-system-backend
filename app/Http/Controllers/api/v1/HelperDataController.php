<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\api\v1\Client;
use App\Models\api\v1\Branch;
use App\Models\api\v1\Car;
use App\Models\api\v1\Category;
use App\Models\api\v1\City;
use App\Models\api\v1\Distribution;
use App\Models\api\v1\District;

class HelperDataController extends Controller
{
    public function foreign()
    {
    	$return['clients'] = Client::all();
    	$return['cities'] = City::all();
    	$return['districts'] = District::all();
    	$return['distributions'] = Distribution::all();
    	return $return;
    }
}
