<?php

namespace App\Http\Controllers\authority_control;

use App\Models\career\Career;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class UniversityController extends BaseController
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

  public function university() {
    // $results = Career::where('status', 1)->get();
    // $results = Career::where('status', 1)->take(200)->get();
    $results = Career::where([
      ['status', 1],
      // ['universityName', '!=', ''],
      // ['universityDepartment', '!=', '']
    ])->get();
    return response()->json($results);
  }

  public function create(Request $request){
    $result = new Career;
    $result->universityName = $request->universityName;
    $result->save();
    return response()->json($this->response); 
  }

  public function edit(Request $request) {
    $results = Career::find($request->id);
    $results->universityName = $request->universityName;
    $results->status = 1;
    $results->save();
    return response()->json($this->response);
  }

  public function delete($id) {
    $results = Career::find($id);
    $results->status = 0;
    $results->save();
    return response()->json($this->response);
  }

}
