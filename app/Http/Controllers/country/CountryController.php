<?php

namespace App\Http\Controllers\country;

use App\Models\person\Person;
use App\Models\country\Country;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ImageController;
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
  private $path = 'images/country/';
  

  public function country() 
  {
    $results = Country::where('status', 1)->orderBy('caption', 'asc')->take(200)->get();
    $images = new ImageController();
    $results = $images->getImagesUrl($results, $this->path, 'flagImage');
    return response()->json($results);
  }
    
  public function create(Request $request)
  {
    $upload = new UploadController();
    $image = $upload->setImage($request, $this->path);
    
    $result = new Country;
    $result->caption = $request->caption;
    $result->ordinal = $request->ordinal;
    $result->flagImage = $image;

    $result->save();
    return response()->json($this->response);
  }

  public function edit(Request $request)
  {
    $upload = new UploadController();
    $image = $upload->setImage($request, $this->path);

    $result = Country::find($request->id);
    $result->caption = $request->caption;
    $result->flagImage = $image;

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
  public function getCountry($results) 
  {
    $results = Country::where('status', 1)->get();
    return $results;
  }

  public function top(Request $request)
  {
      // $results = Country::where('status', 1)
      // ->get(['AddressCountry.id']);
      // //  echo $results;
      // foreach ($results as $result) {
      //   echo $result;
      // $result = Person::where('Person.nationalityAddressCountryId', '=', $result )
      //  ->leftjoin('AddressCountry', 'AddressCountry.id','=','Person.nationalityAddressCountryId')
      //   // ->get(['Person.nationalityAddressCountryId']);
      //   ->count();
      // }
      
      $results = Country::where('status', 1) 
       ->where('AddressCountry.id','>',0)
      ->count();
      $sum=$results;

      $results2 = Person::where('Person.nationalityAddressCountryId','>', 0) 
     ->get(['Person.nationalityAddressCountryId']);

      for($k=1;$k<=$sum;$k++){
        $results2 = Person::where('Person.nationalityAddressCountryId','=', $k)
        ->count();
        $array[]=$results2;  
          if('Person.nationalityAddressCountryId'== $k){
            $resultsMale = Person::where('Person.gender','=', 1);
            // $resultsMale = Person::where('Person.nationalityAddressCountryId','=', $k)
            // ->count();
            // $array[]=$resultsMale;
            }    

      }
        // return response()->json($arrar,$arrayMale);
        return response()->json($array);
//  return response()->json($arrayMale);
  }



  public function top2(Request $request)
  {
      
      $results = Country::where('status', 1) 
       ->where('AddressCountry.id','>',0)
      // ->get(['AddressCountry.id']);
      ->count();
      $sum=$results;
      // echo 
      // echo $results;
      $results2 = Person::where('Person.nationalityAddressCountryId','>', 0) 
     ->get(['Person.nationalityAddressCountryId']);
    //  echo $results2;
      for($k=0;$k<=$sum;$k++){
        // echo $k;
        $results2 = Person::where('Person.nationalityAddressCountryId','=', $k)
        ->count();
          if('Person.nationalityAddressCountryId'== $k){
            $resultsMale = Person::where('Person.gender','=', 0)
            ->count();
            echo $resultsMale;
            $arrayMale[]=$resultsMale;
            }    
$array[]=$results2;  
      }
     
 
        return response()->json($arrayMale);

  }
   
}
