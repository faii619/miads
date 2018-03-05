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

  private $response = array('status' => 1, 'message' => 'success');
  
  public function news_cate()
  {
    $results = NewsCategory::where('status', 1)->get();
    return response()->json($results);
  }

  public function create(Request $request)
  {
    $result = new NewsCategory;
    $result->caption = $request->caption;
    $result->save();
    return response()->json($this->response); 
  }
  
  public function edit(Request $request) 
  {
    $results = NewsCategory::find($request->id);
    $results->caption = $request->caption;
    $results->status = 1;
    $results->save();
    return response()->json($this->response);
  }
  
  public function delete($id) 
  {
    $results = NewsCategory::find($id);
    $results->status = 0;
    $results->save();
    return response()->json($this->response);
  }

}