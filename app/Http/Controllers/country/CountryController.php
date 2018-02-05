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
      
      public function country2(){
        $results = Country::all();
        return response()->json($results);
      }
    public function create(Request $request){
        $result = new Country;
        $result->caption = $request->caption;
        $result->ordinal = $request->ordinal;
        $result->save();
        return response()->json($this->response); 
      }

      public function edit(Request $request){
        $result = Country::find($request->id);
        $result->caption = $request->caption;
        $result->ordinal = $request->ordinal;
        $result->save();
        return response()->json($this->response); 
      }
      public function delete(Request $request){
        $result = Country::find($request->id);
        $result->save();
        return response()->json($this->response); 
      }
    
    
}
