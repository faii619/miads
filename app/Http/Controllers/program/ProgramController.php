<?php

namespace App\Http\Controllers\program;

use App\Models\program\Program;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ProgramController extends BaseController
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

  // public function program_department(){
  //   $results = ProgramDepartment::all();
  //   return response()->json($results);
  // }

  public function create(Request $request){
    $result = new Program;
    $result->code = $request->code;
    $result->title = $request->title;
    $result->startDate = $request->startDate;
    $result->endDate = $request->endDate;
    $result->programDepartmentId = $request->programDepartmentId;
    $result->save();
    return response()->json($this->response); 
  }

  // public function edit(Request $request){
  //   $result = ProgramDepartment::find($request->id);
  //   $result->caption = $request->caption;
  //   $result->save();
  //   return response()->json($this->response);
  // }

  // public function delete(Request $request){
  //   $result = ProgramDepartment::where('id', $request->id)->delete();
  //   return response()->json($this->response);
  // }
}
