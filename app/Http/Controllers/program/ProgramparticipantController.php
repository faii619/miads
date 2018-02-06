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
  
  public function participants($id) {
    $results = Programparticipant::where('programId', $id)
    ->leftJoin('program', 'Programparticipant.programId', '=', 'program.id')  
    ->leftJoin('alumni', 'Programparticipant.alumniId', '=', 'alumni.id')  
    ->leftJoin('person', 'alumni.personId', '=', 'person.id')  
    ->leftJoin('persontitle', 'person.personTitleId', '=', 'persontitle.id')  
    ->leftJoin('file', 'person.photoFileId', '=', 'file.id')  
    ->leftJoin('addresscountry', 'person.nationalityAddressCountryId', '=', 'addresscountry.id')  
    ->get();
    return response()->json($results);
  }

  public function enroll(Request $request) {
    $results = new Programparticipant;
    $results->programId = $request->programId;
    $results->alumniId = $request->alumniId;
    $results->save();
    return response()->json($this->response); 
  }
}
