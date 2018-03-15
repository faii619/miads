<?php

namespace App\Http\Controllers\news;

use App\Http\Controllers\mail\MailController;
use App\Models\news\News;
use App\Models\news_subscription\NewsSubscription;
use App\Models\news_news_category\NewsNewsCategory;
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
      'News.*',
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

  public function create(Request $request)
  {
    $newsId = 0;
    $NewsSendId = 0;
    $recipient['group'] = '';
    $recipient['man'] = '';

    $results = new News;
    $results->title = $request->title;
    $results->body = $request->body;
    $results->status = 1;
    $results->statusSending = $request->statusSending;
    $results->save();
    $newsId = $results->id;

    $data = array(
                  'title' => $request->title
                  , 'body' => $request->body
                );

    if ($request->newsCategoryId != 0) {
      $results3 = new NewsNewsCategory;
      $results3->newsId = $newsId;
      $results3->newsCategoryId = $request->newsCategoryId;
      $results3->save();

      $recipient['group'] = $this->getPersonSubscription($request->newsCategoryId);

      if ($request->statusSending == 1) {
        $this->sendMail($recipient['group'], $data, $NewsSendId);
      }
    }

    if ($request->to != "0") {
      $results2 = new NewsSendTo;
      $results2->newsId = $newsId;
      $results2->sendTo = $request->to;
      $results2->sendCC = $request->cc;
      $results2->sendBCC = $request->bcc;
      $results2->save();
      $NewsSendId = $results2->id;

      $recipient['man'] = [[
                    'to' => $request->to
                    , 'cc' => $request->cc
                    , 'bcc' => $request->bcc
                  ]];

      if ($request->statusSending == 1) {
        $xxx['rec2'] = $this->sendMail($recipient['man'], $data, $NewsSendId);
      }
    }

    return response()->json($this->response);
  }

  public function getPersonSubscription($newsCateId)
  {
    $result = [];
    $rs = NewsSubscription::where([
                                    ['newsCategoryId', $newsCateId]
                                    , ['status', 1]
                                  ])
                  ->leftjoin('Person', 'NewsSubscription.personId', '=', 'Person.id')
                  ->get(['Person.email', 'Person.otherEmails']);

    foreach ($rs as $key => $value) {
      $result[$key]['to'] = ($value['email'] != '' ? $value['email'] : 0);
      $result[$key]['cc'] = ($value['otherEmails'] != '' ? $value['otherEmails'] : 0);
      $result[$key]['bcc'] = 0;
    }

    return $result;
  }

  public function sendMail($recipient, $data, $NewsSendId)
  {
    $set_data = [];
    foreach ($recipient as $key => $value) {
      $set_data = array(
                        'email' => $value['to']
                        , 'email_cc' => $value['cc']
                        , 'email_bcc' => $value['bcc']
                        , 'subject' => $data['title']
                        , 'body' => $data['body']
                      );

      $email = new MailController;
      $statusSend = $email->send_email($set_data);

      if ($NewsSendId != 0 && $statusSend == 1) {
        $resultNewsSend = NewsSendTo::find($NewsSendId);
        $resultNewsSend->status = 1;
        $resultNewsSend->save();
      }
    }
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
