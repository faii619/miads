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
    // public function create()
    // {
    //     $results = Country::all();
    //     return response()->json($results);
    // }

    public function create(Request $request)
    {
      $instance = new Country;
  
      $instance->social_user_id = $request->social_user_id;
      $instance->name = $request->name;
      $instance->email = $request->email;
g
      $instance->save();
  
      return response()->json($this->response);

    }

    
}
