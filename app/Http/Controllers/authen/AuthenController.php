<?php

namespace App\Http\Controllers\persontitle;

use App\Models\persontitle\PersonTitle;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthenController extends BaseController {
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      //
  }

  public function authen(Request $request)
  {
    return response()->json($request);
  }
  
}
