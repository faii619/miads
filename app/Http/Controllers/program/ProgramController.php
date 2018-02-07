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

  private $response = array('status'=>1, 'message' => 'success');
  
  private function get_dates_en($results) {
    foreach ($results as $key => $value) {
      $results[$key]['startDate'] = date("d/m/Y", strtotime($value['startDate']));
      $results[$key]['endDate'] = date("d/m/Y", strtotime($value['endDate']));
    }

    return $results;
  }

  public function programs_by_conditions(Request $request) {
    $results = Program::where('status', 1)->get();
    $results = $this->get_dates_en($results);

    return response()->json($results);
  }

  public function find($id) {
    $results = Program::find($id);
    $results['startDate'] = date("d/m/Y", strtotime($results['startDate']));
    $results['endDate'] = date("d/m/Y", strtotime($results['endDate']));
    return response()->json($results);
  }

  public function create(Request $request) {
    $results = new Program;
    $results->code = $request->code;
    $results->title = $request->title;
    $results->startDate = date("Y-m-d", strtotime($request->startDate));
    $results->endDate = date("Y-m-d", strtotime($request->endDate));
    $results->programDepartmentId = $request->programDepartmentId;
    $results->status = 1;
    $results->save();
    return response()->json($this->response); 
  }

  public function edit(Request $request) {
    $startDate = str_replace('/', '-', $request->startDate);
    $endDate = str_replace('/', '-', $request->endDate);
    
    $results = Program::find($request->id);
    $results->code = $request->code;
    $results->title = $request->title;
    $results->startDate = date("Y-m-d", strtotime($startDate));
    $results->endDate = date("Y-m-d", strtotime($endDate));
    $results->programDepartmentId = $request->programDepartmentId;
    $results->status = 1;
    $results->save();
    return response()->json($this->response);
  }

  public function delete($id) {
    $results = Program::find($id);
    $results->status = 0;
    $results->save();

    return response()->json($this->response);
  }
}
