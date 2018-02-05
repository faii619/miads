<?php

namespace App\Http\Controllers\program;

use App\Models\program\Program;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ProgramController extends BaseController {
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

  // public function find(Request $request) {
  // }

  public function create(Request $request) {
    $result = new Program;
    $result->code = $request->code;
    $result->title = $request->title;
    $result->startDate = $request->startDate;
    $result->endDate = $request->endDate;
    $result->programDepartmentId = $request->programDepartmentId;
    $result->save();
    return response()->json($this->response); 
  }

  public function edit(Request $request) {
    $result = Program::find($request->id);
    $result->code = $request->code;
    $result->title = $request->title;
    $result->startDate = $request->startDate;
    $result->endDate = $request->endDate;
    $result->programDepartmentId = $request->programDepartmentId;
    $result->save();
    return response()->json($this->response);
  }

  public function delete(Request $request) {
    $result = Program::where('id', $request->id)->delete();
    return response()->json($this->response);
  }
  
  // public function participants(Type $var = null) {
  // }

  // public function enroll(Request $request) {
  //   $result = new Programparticipant;
  //   $result->programId = $request->programId;
  //   $result->alumniId = $request->alumniId;
  //   $result->save();
  //   return response()->json($this->response); 
  // }
}
