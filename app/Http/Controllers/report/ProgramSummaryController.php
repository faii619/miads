<?php

namespace App\Http\Controllers\report;

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
    $results = Programparticipant::where('ProgramParticipant.status', 1)
    ->leftJoin('Program', 'ProgramParticipant.programId', '=', 'Program.id')  
    ->get();
    return response()->json($results);
  }
}
