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
  public function news_cate(){
    $results = NewsCategory::all();
    return response()->json($results);
  }

  public function create(Request $request) {
    $results = new NewsCategory;
    $results->caption = $request->caption;
    $results->save();
    return response()->json($this->response);
  }

  public function edit(Request $request) {
    $results = NewsCategory::find($request->id);
    $results->caption = $request->caption;
    $results->save();
    return response()->json($this->response);
  }

  public function delete($id) {
    NewsCategory::where('id', $id)->delete();
    return response()->json($this->response);
  }
}
