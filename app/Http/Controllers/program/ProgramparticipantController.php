<?php

namespace App\Http\Controllers\program;

use App\Models\program\Programparticipant;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ProgramparticipantController extends BaseController {
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
  
  // public function participants(Type $var = null) {
  // }

  public function enroll(Request $request) {
    $result = new Programparticipant;
    $result->programId = $request->programId;
    $result->alumniId = $request->alumniId;
    $result->save();
    return response()->json($this->response); 
  }
}
