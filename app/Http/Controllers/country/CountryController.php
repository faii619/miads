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

    private $response = array( 'message' => 'success');

    public function country(){
        $results = Country::all();
        return response()->json($results);
      }

    // public function create(Request $request)
    // {
    // $instance = new Country;
    //   $instance->id = $request->input('id','46');
    //   $instance->caption = $request->input('caption', 'maha');
    //   $instance->ordinal = $request->input('ordinal', '100');
    //   return response()->json($this->response);

    // }
    // public function create(Request $request){
    //     $result = new Country;
    //     $result->caption = $request->caption;
    //     $result->ordinal = $request->ordinal;
    //     $result->save();
    //     return response()->json($this->response); 
    //   }
    
    
}
