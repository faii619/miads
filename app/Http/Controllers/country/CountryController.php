<?php

namespace App\Http\Controllers\country;

use App\Models\country\Country;
use App\Http\Controllers\UploadController;
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
  private $path = 'images/country_images';

  public function country() 
  {
    // $results = Career::where('status', 1)->get();
    $results = Country::where('status', 1)->take(200)->get();
    return response()->json($results);
  }
    
    public function create(Request $request)
    {
      $upload = new UploadController();
      $image = $upload->setImage($request, $this->path);
      
      $result = new Country;
      $result->caption = $request->caption;
      $result->flagFileId = $image;

      $result->save();
      return response()->json($this->response); 
    }

    public function edit(Request $request)
    {
      $result = Country::find($request->id);
      $result->caption = $request->caption;
      // $result->ordinal = $request->ordinal;
      $result->save();
      return response()->json($this->response); 
    }
    
    public function delete($id) 
    {
      $results = Country::find($id);
      $results->status = 0;
      $results->save();
      return response()->json($this->response);
    }
   
}
