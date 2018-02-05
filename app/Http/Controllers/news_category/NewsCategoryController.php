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

<<<<<<< HEAD
  // public function find(Request $request) {
  // }
  public function news_cate(){
    $results = Program::all();
    return response()->json($results);
  }

=======
>>>>>>> 70f752f343310f7955877c50e74810fe5b42a7a7
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
