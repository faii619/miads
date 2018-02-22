<?php

namespace App\Http\Controllers\CountrySummaryController;

use App\Models\person\Person;
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

  private $response = array('status' => 1, 'message' => 'success');

  public function country_summary() {
    $results = Person::where([
      ['personStatus', 1],
      ['gender', '=', '1'],
      ['nationalityAddressCountryId', '=', '1']
    ])->get();
    // ['Person.gender']
    return response()->json($results);
  }
}
