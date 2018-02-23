<?php

namespace App\Http\Controllers\report;



// use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\country\Country;
use App\Http\Controllers\UploadController;
use App\Models\person\Person;
use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class CountrySummaryController extends BaseController {
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      //
  }

  private $response = array('status'=>1,'message' => 'success');
  // private $path = 'images/alumni/';
  private $path = 'images/country/';

  public function country_summary_all() {
    $results = Country::where('AddressCountry.status', 1)
    ->limit(1)
    ->get();
    foreach ($results as $key => $value) {
      $results[$key]['countmaleall'] = $this->countmaleall();
      $results[$key]['countfemaleall'] = $this->countfemaleall();
      $results[$key]['countcountryall'] = $this->countcountryall();
      $results[$key]['countundefineall'] = $this->countundefineall();
    }
    return response()->json($results);
  }
  public function country_summary() {
         $results = Country::where('AddressCountry.status', 1)
    ->orderBy('caption', 'asc')
    // ->orderBy($data, 'asc')
    ->get();
      $images = new ImageController();
    $results = $images->getImagesUrl($results, $this->path, 'flagImage');
    foreach ($results as $key => $value) {
      $results[$key]['countcountry'] = $this->countcountry($value['id']);
      $results[$key]['countmale'] = $this->countmale($value['id']);
      $results[$key]['countfemale'] = $this->countfemale($value['id']);
      $results[$key]['countundefine'] = $this->countundefine($value['id']);
      $results[$key]['countfemaleal'] = $this->countfemaleall();
      // $results[$key]['caption'] = $this->countcountrycaption();
    }
     return response()->json($results);
  }
  public function country_summary_max() {
    $results = Country::where('AddressCountry.status', 1)
    // ->orderBy('caption', 'asc')
    // ->limit(11) 
    // ->groupby('countcountry')
    ->get();  
    foreach ($results as $key => $value) {
    $results[$key]['countcountry'] = $this->countcountry($value['id']);
    $results[$key]['cap'] = $this->countcountry($value['id']);
   $start = $this->country_na(['caption']);  
   $arraycap[]= $results[$key]['cap'];
    $array[]= $results[$key]['countcountry'] ;
    rsort($array);
    // $array2[]= $results[$key]['countcountry'] ;
     } 
     for ($i=0;$i<10;$i++){
    for ($j=0;$j<12;$j++){
       if($array[$i]===$arraycap[$j]){

          // if($start[''])
      // $results3[]['cap']= $this->countcountry($id);
        $results3[]['caption'] = $start[$j]; 
         $results3[]['count']= $array[$i];

      }
    }
  }


  //   usort($results, function($a, $b) { //Sort the array using a user defined function
  //     return $a->score > $b->score ? -1 : 1; //Compare the scores
  // });  

//  $posts = Country::with($results)->all()->sortBy(function ($item) {
//   return $item->votes->count();
// }, SORT_REGULAR, true)->take(10);

// return $results;
return response()->json($results3);
}
public function country_na() {
  $results = Country::where('AddressCountry.status', 1)
  // ->orderBy('caption', 'asc')
  // ->limit(11) 
  // ->groupby('countcountry')
  ->get(['caption']);
  return $results;
}
  public function countcountry($id) {
    $results = Person::where('Person.personStatus', 1)
    ->where('Person.nationalityAddressCountryId', $id)
    // ->orderBy('Person.nationalityAddressCountryId','desc')
    ->count();
    return $results;
  }
  public function countcountrycaption() {
    $results = Person::where('Person.personStatus', 1)
    // ->where('Person.nationalityAddressCountryId', )
    // ->orderBy('Person.nationalityAddressCountryId','desc')
    ->count();
    return $results;
  }
  public function countcountryall() {
    $results = Person::where('Person.personStatus', 1)
    ->where('Person.nationalityAddressCountryId','>',0)
    ->count();
    return $results;
 
  }
  public function countmale($id) {
    $results = Person::where('Person.personStatus', 1)
    ->where('Person.gender', 1)
    ->where('Person.nationalityAddressCountryId', $id)
    ->count();
    return $results;
  }
  public function countfemale($id) {
    $results = Person::where('Person.personStatus', 1)
    ->where('Person.gender', 0)
    ->where('Person.nationalityAddressCountryId', $id)
    ->count();
    return $results;
  }
  public function countundefine($id) {
    $results = Person::where('Person.personStatus', 1)
    ->where('Person.gender',null)
    ->where('Person.nationalityAddressCountryId', $id)
    ->count();
    return $results;
  }
  public function countmaleall() {
    $results = Person::where('Person.personStatus', 1)
    ->where('Person.gender', 1)
    ->count();
    return $results;
  }
  public function countfemaleall() {
    $results = Person::where('Person.personStatus', 1)
    ->where('Person.gender', 0)
    ->count();
    return $results;
  }
  public function countundefineall() {
    $results = Person::where('Person.personStatus', 1)
    ->where('Person.gender',null)
    ->count();
    return $results;
  }
  

}
