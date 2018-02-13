<?php

namespace App\Http\Controllers\careerorganizationtype;

use App\Models\careerorganizationtype\CareerOrganizationType;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class CareerOrganizationTypeController extends BaseController {
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      //
  }

  public function careerorganizationtype()
  {
    $results = CareerOrganizationType::all();
    return response()->json($results);
  }
  
}
