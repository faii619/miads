<?php

namespace App\Http\Controllers\authority_control;

use App\Models\career\Career;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class OrganizationController extends BaseController
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

  public function organization() {
    // $results = Career::where('status', 1)->get();
    // $results = Career::where('status', 1)->take(200)->get();
    $results = Career::where([
      ['status', 1],
      // ['organizationName', '!=', ''],
      // ['organizationDepartment', '!=', '']
    ])->get();
    return response()->json($results);
  }

  public function create(Request $request){
    $result = new Career;
    $result->organizationName = $request->organizationName;
    $result->save();
    return response()->json($this->response); 
  }

  public function edit(Request $request) {
    $results = Career::find($request->id);
    $results->organizationName = $request->organizationName;
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
