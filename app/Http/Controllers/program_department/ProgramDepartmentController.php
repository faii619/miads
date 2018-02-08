<?php

namespace App\Http\Controllers\program_department;

use App\Models\program_department\ProgramDepartment;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ProgramDepartmentController extends BaseController
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

  public function program_department() {
    // $results = ProgramDepartment::where('status', 1)->get();
    $results = ProgramDepartment::where('status', 1)->take(200)->get();
    return response()->json($results);
  }

  public function create(Request $request) {
    $results = new ProgramDepartment;
    $results->caption = $request->caption;
    $results->save();
    return response()->json($this->response);
  }

  public function edit(Request $request) {
    $results = ProgramDepartment::find($request->id);
    $results->caption = $request->caption;
    $results->status = 1;
    $results->save();
    return response()->json($this->response);
  }

  public function delete($id) {
    $results = ProgramDepartment::find($id);
    $results->status = 0;
    $results->save();
    return response()->json($this->response);
  }
}
