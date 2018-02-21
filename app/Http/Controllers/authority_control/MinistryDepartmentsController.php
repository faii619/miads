<?php

namespace App\Http\Controllers\authority_control;

use App\Models\career\Career;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class MinistryDepartmentsController extends BaseController
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

  public function ministry_departments() {
    // $results = Career::where('status', 1)->get();
    // $results = Career::where('status', 1)->take(200)->get();
    $results = Career::where([
      ['status', 1],
      // ['govMinistryName', '!=', ''],
      ['govDepartmentName', '!=', '']
    ])->get();
    
    return response()->json($results);
  }

  public function create(Request $request) {
    $result = new Career;
    $result->govDepartmentName = $request->govDepartmentName;
    $result->save();
    return response()->json($this->response); 
  }

  public function edit(Request $request) {
    $results = Career::find($request->id);
    $results->govDepartmentName = $request->govDepartmentName;
    $results->status = 1;
    $results->save();
    return response()->json($this->response);
  }

}
