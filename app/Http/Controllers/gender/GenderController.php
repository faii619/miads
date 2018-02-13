<?php

namespace App\Http\Controllers\gender;

use App\Models\gender\Gender;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class GenderController extends BaseController {
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      //
  }

  public function gender()
  {
    $results = Gender::all();
    return response()->json($results);
  }
  
}
