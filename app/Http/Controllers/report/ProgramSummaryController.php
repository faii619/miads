<?php

namespace App\Http\Controllers\report;

use App\Models\program\Program;
use App\Models\program\Programparticipant;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ProgramSummaryController extends BaseController {
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      //
  }

  private $response = array('status'=>1,'message' => 'success');

  public function program_summary() {
    $results = Program::where('Program.status', 1)->get();
    foreach ($results as $key => $value) {
      $results[$key]['count'] = $this->count($value['id']);;
    }
    return response()->json($results);
  }

  public function count($id) {
    $results = Programparticipant::where('ProgramParticipant.programId', $id)
    ->where('ProgramParticipant.status', 1)
    ->count();
    return $results;
  }
}
