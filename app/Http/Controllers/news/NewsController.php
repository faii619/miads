<?php

namespace App\Http\Controllers\news;

use App\Models\news\News;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class NewsController extends BaseController {
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      //
  }

  public function News()
  {
    $results = News::where('status', 1)->orderBy('id', 'desc')->get();
    // $results = News::where('status', 1)->get();
    return response()->json($results);
  }
}
