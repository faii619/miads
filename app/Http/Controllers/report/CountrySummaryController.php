<?php

namespace App\Http\Controllers\report;



use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\country\Country;
use App\Http\Controllers\UploadController;
use App\Models\program\Program;
use App\Models\program\Programparticipant;
use App\Models\person\Person;
use App\Http\Controllers\ImageController;
use App\Models\career\Career;
use Illuminate\Http\Request;
// use Laravel\Lumen\Routing\Controller as BaseController;

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
  private $path = 'images/alumni/';

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
    ->get();
    foreach ($results as $key => $value) {
      $results[$key]['countcountry'] = $this->countcountry($value['id']);
      $results[$key]['countmale'] = $this->countmale($value['id']);
      $results[$key]['countfemale'] = $this->countfemale($value['id']);
      $results[$key]['countundefine'] = $this->countundefine($value['id']);
      $results[$key]['countfemaleal'] = $this->countfemaleall();
    }
    return response()->json($results);
  }

  public function countcountry($id) {
    $results = Person::where('Person.personStatus', 1)
    ->where('Person.nationalityAddressCountryId', $id)
    ->count();
    return $results;
  }
  public function countcountryall() {
    $results = Person::where('Person.personStatus', 1)
    ->where('Person.nationalityAddressCountryId','>',0)
    ->count();
    // foreach ($results as $key => $value) {
      // $value['countcountryall']=$results ;
    return $results;
    // return response()->json($value);
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
  
  
  // $images = new ImageController();
  //   $results = $images->getImagesUrl($results, $this->path, 'fileName');
  //   return $results;
}
