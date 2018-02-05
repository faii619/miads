<?php

namespace App\Http\Controllers\news_category;

use App\Models\news_category\NewsCategory;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class NewsCategoryController extends BaseController {
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
    $results = new Program;
    $results->code = $request->code;
    $results->title = $request->title;
    $results->startDate = $request->startDate;
    $results->endDate = $request->endDate;
    $results->programDepartmentId = $request->programDepartmentId;
    $results->save();
    return response()->json($this->response); 
  }

  public function edit(Request $request) {
    $results = Program::find($request->id);
    $results->code = $request->code;
    $results->title = $request->title;
    $results->startDate = $request->startDate;
    $results->endDate = $request->endDate;
    $results->programDepartmentId = $request->programDepartmentId;
    $results->save();
    return response()->json($this->response);
  }

  public function delete($id) {
    $result = Program::where('id', $id)->delete();
    return response()->json($this->response);
  }
}
