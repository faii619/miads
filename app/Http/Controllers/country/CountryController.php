<?php

namespace App\Http\Controllers\country;

use App\Models\country\Country;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class CountryController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    private $response = array('status' => 1, 'message' => 'success');
    public function country(){
        $results = Country::all();
        return response()->json($results);
      }

    public function create(Request $request)
    {
      $instance = new Country;
    //   $instance->id = $request->input('id', '');
    //   $instance->caption = $request->input('caption', 'maha');
    //   $instance->ordinal = $request->input('ordinal', '5000');
     //   $result->caption = $request->caption;
      $instance->save();
    //   $name = $request->input('name', 'Sally');
      return response()->json($this->response);
    // return "kk";
    }
    
    
}
