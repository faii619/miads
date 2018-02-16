<?php

namespace App\Http\Controllers\dashboard;

use App\Models\person\Person;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class DashboardController extends BaseController {
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

  public function dashboard() {
    // $results = Person::where('status', 1)->get();
    $results = Person::where('status', 1)->take(200)->get();
    return response()->json($results);
  }
}
