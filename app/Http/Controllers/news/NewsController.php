<?php

namespace App\Http\Controllers\news;

use App\Http\Controllers\mail\MailController;
use App\Models\news\News;
use App\Models\news_send_to\NewsSendTo;
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
    $item = [
      'News.id',
      'News.title',
      'News.body',
      'MailBatch.progress'
    ];
    $results = News::where('News.status', 1)
    ->leftJoin('News_NewsCategory', 'News.id', '=', 'News_NewsCategory.newsId')
    // ->leftJoin('NewsCategory', 'News_NewsCategory.newsCategoryId', '=', 'NewsCategory.id')
    // ->leftJoin('NewsAttachment', 'News.id', '=', 'NewsAttachment.newsId')
    // ->leftJoin('File', 'NewsAttachment.fileId', '=', 'File.id')
    ->leftJoin('MailBatch', 'News.mailBatchId', '=', 'MailBatch.id')
    // ->leftJoin('MailStatus', 'News.mailBatchId', '=', 'MailStatus.mailBatchId')
    // ->leftJoin('Person', 'MailStatus.personId', '=', 'Person.id')
    ->orderBy('News.id', 'desc')
    ->get($item);
    return response()->json($results);
  }

  public function find($id)
  {
    $item = [
      'News.id',
      'News.title',
      'News.body',
      'News_NewsCategory.newsCategoryId'
    ];
    $results = News::where('News.status', 1)
    ->where('News.id', $id)
    ->leftJoin('News_NewsCategory', 'News.id', '=', 'News_NewsCategory.newsId')
    ->leftJoin('NewsCategory', 'News_NewsCategory.newsCategoryId', '=', 'NewsCategory.id')
    // ->leftJoin('NewsAttachment', 'News.id', '=', 'NewsAttachment.newsId')
    // ->leftJoin('File', 'NewsAttachment.fileId', '=', 'File.id')
    // ->leftJoin('MailBatch', 'News.mailBatchId', '=', 'MailBatch.id')
    // ->leftJoin('MailStatus', 'News.mailBatchId', '=', 'MailStatus.mailBatchId')
    // ->leftJoin('Person', 'MailStatus.personId', '=', 'Person.id')
    // News_NewsCategory
    ->get($item);
    return response()->json($results);
  }

  public function create(Request $request) {
    $results = new News;
    $results->title = $request->title;
    $results->body = $request->body;
    $results->status = 1;
    $results->statusSending = $request->statusSending;
    $results->save();
    $newsId = $results->id;

    $results2 = new NewsSendTo;
    $results2->newsId = $newsId;
    $results2->sendTo = $request->to;
    $results2->sendCC = $request->cc;
    $results2->sendBCC = $request->bcc;
    $results2->save();
    $NewsSendId = $results2->id;
    
    $data = array(
      'email' => $request->to
      , 'email_cc' => $request->cc
      , 'email_bcc' => $request->bcc
      , 'subject' => $request->title
      , 'body' => $request->body
    );

    $email = new MailController;
    $statusSend = $email->send_email($data);

    if ($statusSend == 1) {
      $resultNewsSend = NewsSendTo::find($NewsSendId);
      $resultNewsSend->status = 1;
      $resultNewsSend->save();
    }
    
    return response()->json($this->response);
    // return response()->json($statusSend);
  }

  public function edit(Request $request) {
    $results = News::find($request->id);
    $results->title = $request->title;
    $results->body = $request->body;
    $results->status = 1;
    $results->save();
    return response()->json($this->response);
  }
  
  public function delete($id) {
    $results = News::find($id);
    $results->status = 0;
    $results->save();
    return response()->json($this->response);
  }
}
