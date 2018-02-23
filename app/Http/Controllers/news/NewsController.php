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

  private $response = array('status' => 1, 'message' => 'success');  

  public function News()
  {
    $results = News::where('News.status', 1)
    ->leftJoin('News_NewsCategory', 'News.id', '=', 'News_NewsCategory.newsId')
    ->leftJoin('NewsCategory', 'News_NewsCategory.newsCategoryId', '=', 'NewsCategory.id')
    ->leftJoin('NewsAttachment', 'News.id', '=', 'NewsAttachment.newsId')
    ->leftJoin('File', 'NewsAttachment.fileId', '=', 'File.id')
    // ->leftJoin('MailBatch', 'News.mailBatchId', '=', 'MailBatch.id')
    // ->leftJoin('MailStatus', 'News.mailBatchId', '=', 'MailStatus.mailBatchId')
    // ->leftJoin('Person', 'MailStatus.personId', '=', 'Person.id')
    ->orderBy('News.id', 'desc')
    ->get(['News.*', 'NewsCategory.caption']);
    return response()->json($results);
  }

  public function find($id)
  {
    $results = News::where('News.status', 1)
    ->where('News.id', $id)
    ->leftJoin('News_NewsCategory', 'News.id', '=', 'News_NewsCategory.newsId')
    ->leftJoin('NewsCategory', 'News_NewsCategory.newsCategoryId', '=', 'NewsCategory.id')
    // ->leftJoin('NewsAttachment', 'News.id', '=', 'NewsAttachment.newsId')
    // ->leftJoin('File', 'NewsAttachment.fileId', '=', 'File.id')
    // ->leftJoin('MailBatch', 'News.mailBatchId', '=', 'MailBatch.id')
    // ->leftJoin('MailStatus', 'News.mailBatchId', '=', 'MailStatus.mailBatchId')
    // ->leftJoin('Person', 'MailStatus.personId', '=', 'Person.id')
    ->orderBy('News.id', 'desc')
    ->get();
    return response()->json($results);
  }
  
  public function delete($id) {
    $results = News::find($id);
    $results->status = 0;
    $results->save();
    return response()->json($this->response);
  }

  // public function create(Request $request) {
  //   $results = new News;
  //   $results->title = $request->title;
  //   $results->body = $request->body;
  //   $results->status = 1;
  //   $results->save();
  //   return response()->json($request);
  //   return response()->json($this->response);
  // }
}
