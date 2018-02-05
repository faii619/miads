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

  private $response = array('message' => 'success');

  public function program_department(){
    $results = ProgramDepartment::all();
    return response()->json($results);
  }

  public function create(Request $request){
    $result = new ProgramDepartment;
    $result->caption = $request->caption;
    $result->save();
    return response()->json($this->response); 
  }

  public function edit(Request $request){
    $result = ProgramDepartment::find($request->id);
    $result->caption = $request->caption;
    $result->save();
    return response()->json($this->response);
  }

  // public function delete(Request $request){
  //   $result->save();
  //   return response()->json($this->response);
  // }
}
