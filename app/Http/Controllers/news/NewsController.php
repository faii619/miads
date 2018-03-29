<?php

namespace App\Http\Controllers\news;

use App\Http\Controllers\ImageController;
use App\Http\Controllers\mail\MailController;
use App\Http\Controllers\UploadController;
use App\Models\news\News;
use App\Models\news_subscription\NewsSubscription;
use App\Models\news_news_category\NewsNewsCategory;
use App\Models\news_send_to\NewsSendTo;
use App\Models\news_attachment\NewsAttachment;
use App\Models\file\File;
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
  private $path = 'file_attachment/';

  public function News()
  {
    $item = [
              'News.*',
              'MailBatch.progress'
            ];

    $results = News::where('News.status', 1)
                ->leftJoin('News_NewsCategory', 'News.id', '=', 'News_NewsCategory.newsId')
                ->leftJoin('MailBatch', 'News.mailBatchId', '=', 'MailBatch.id')
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

    $rs['data'] = News::where([
                                ['News.id', $id]
                                , ['News.status', 1]
                              ])
                ->leftJoin('News_NewsCategory', 'News.id', '=', 'News_NewsCategory.newsId')
                ->get($item);

    $rs['sendByMan'] = NewsSendTo::where('newsId', $id)->get();
    
    $rs['att'] = $this->getFileAttachment($id);

    return response()->json($rs);
  }

  public function getFileAttachment($id)
  {
    $rs = NewsAttachment::where('newsId', $id)
            ->leftJoin('File', 'NewsAttachment.fileId', '=', 'File.id')
            ->get();
    
    $images = new ImageController();
    $rs = $images->getImagesUrl($rs, $this->path, 'fileName');
    return $rs;
  }

  public function create(Request $request)
  {
    $file_name = '';
    $image = 0;
    $recipient['group'] = [];
    $recipient['man'] = [];

    $results = new News;
    $results->title = $request->title;
    $results->body = $request->body;
    $results->status = 1;
    $results->statusSending = $request->statusSending;
    $results->save();
    $newsId = $results->id;

    if ($request->image != 0) {
      $upload = new UploadController();
      $image = $upload->setImage($request, $this->path);
    }

    $newsFile = new File;
    $newsFile->fileName = $image;
    $newsFile->save();
    $fileId = $newsFile->id;

    $newsAtt = new NewsAttachment;
    $newsAtt->newsId = $newsId;
    $newsAtt->fileId = $fileId;
    $newsAtt->save();

    $attachment = $this->getFileAttachment($newsId);
    $file_name = $attachment[0]['fileName'];
    $file = (($file_name != 'default.png') && ($file_name != 0)) ? $attachment[0]['fileName_url'] : 0 ;

    $data = array(
                  'title' => $request->title
                  , 'body' => $request->body
                  , 'file' => $file
                  , 'file_name' => $file_name
                );

    if ($request->newsCategoryId != 0) {
      $results3 = new NewsNewsCategory;
      $results3->newsId = $newsId;
      $results3->newsCategoryId = $request->newsCategoryId;
      $results3->save();
    }

    $results2 = new NewsSendTo;
    $results2->newsId = $newsId;
    $results2->sendTo = $request->to;
    $results2->sendCC = $request->cc;
    $results2->sendBCC = $request->bcc;
    $results2->save();
    $NewsSendId = $results2->id;

    if ($request->statusSending == 1) {
      if ($request->newsCategoryId != 0) {
        $recipient['group'] = $this->getPersonSubscription($request->newsCategoryId);
        $this->sendMail($recipient['group'], $data, $NewsSendId);
      }

      if($request->to != 0) {
        $recipient['man'] = [[
                  'to' => $request->to
                  , 'cc' => $request->cc
                  , 'bcc' => $request->bcc
                ]];
        $this->sendMail($recipient['man'], $data, $NewsSendId);
      }
    }

    return response()->json($this->response);
  }

  public function getPersonSubscription($newsCateId)
  {
    $result = [];
    $rs = NewsSubscription::where([
                ['NewsSubscription.newsCategoryId', $newsCateId]
                , ['NewsSubscription.status', 1]
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
                        , 'file' => $data['file']
                        , 'file_name' => $data['file_name']
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

  public function edit(Request $request)
  {
    $newsId = $request->id;
    $fileId = $request->fileId;
    $recipient['group'] = '';
    $recipient['man'] = '';

    News::where([['id', '=', $newsId]])
        ->update([
                  'title' => $request->title,
                  'body' => $request->body,
                  'statusSending' => $request->statusSending
                ]);

    if ($request->image != 0) {
      $upload = new UploadController();
      $image = $upload->setImage($request, $this->path);

      File::where([['id', '=', $fileId]])
          ->update([
                    'fileName' => $image
                  ]);
    }

    NewsSendTo::where([['id', '=', $request->sendByManId]])
        ->update([
                  'sendTo' => $request->to,
                  'sendCC' => $request->cc,
                  'sendBCC' => $request->bcc
                ]);

    $attachment = $this->getFileAttachment($newsId);
    $file_name = $attachment[0]['fileName'];
    if (($file_name != 'default.png') && ($file_name != 0)) {
      $file = $attachment[0]['fileName_url'];
    }

    $data = array(
                  'title' => $request->title
                  , 'body' => $request->body
                  , 'file' => $file
                  , 'file_name' => $file_name
                );

    if ($request->newsCategoryId != 0) {
      $row = NewsNewsCategory::where([
                ['newsId', '=', $newsId],
                ['newsCategoryId', '=', $request->newsCategoryId]
              ])->count();

      if ($row <= 0) {
        $results3 = new NewsNewsCategory;
        $results3->newsId = $newsId;
        $results3->newsCategoryId = $request->newsCategoryId;
        $results3->save();
      } else {
        NewsNewsCategory::where([['newsId', '=', $newsId]])
            ->update([
                      'newsCategoryId' => $request->newsCategoryId
                    ]);
      }
    }

    if ($request->statusSending == 1) {
      if ($request->newsCategoryId != 0) {
        $recipient['group'] = $this->getPersonSubscription($request->newsCategoryId);
        $this->sendMail($recipient['group'], $data, 0);
      }

      if ($request->to != "0") {
        $recipient['man'] = [[
                              'to' => $request->to
                              , 'cc' => $request->cc
                              , 'bcc' => $request->bcc
                            ]];
        $this->sendMail($recipient['man'], $data, $request->sendByManId);
      }
    }

    return response()->json($this->response);
  }
  
  public function delete($id) {
    $results = News::find($id);
    $results->status = 0;
    $results->save();
    return response()->json($this->response);
  }

  public function sendEMail($id)
  {
    $file = 0;
    $file_name = '';
    $recipient['group'] = '';
    $recipient['man'] = '';
    $item = [
              'News.id',
              'News.title',
              'News.body',
              'News_NewsCategory.newsCategoryId'
            ];

    $dataMail = News::where([
                            ['News.id', $id]
                            , ['News.status', 1]
                          ])
                ->leftJoin('News_NewsCategory', 'News.id', '=', 'News_NewsCategory.newsId')
                ->get($item);

    $sendByMan = NewsSendTo::where('newsId', $id)->get();

    $attachment = $this->getFileAttachment($id);
    $file_name = $attachment[0]['fileName'];
    if (($file_name != 'default.png') && ($file_name != 0)) {
      $file = $attachment[0]['fileName_url'];
    }

    $data = array(
                  'title' => $dataMail[0]['title']
                  , 'body' => $dataMail[0]['body']
                  , 'file' => $file
                  , 'file_name' => $file_name
                );

    if ($dataMail[0]['newsCategoryId'] != null) {
      $recipient['group'] = $this->getPersonSubscription($dataMail[0]['newsCategoryId']);
      $this->sendMail($recipient['group'], $data, 0);
    }

    if (!empty($sendByMan[0])) {
      $recipient['man'] = [[
                            'to' => $sendByMan[0]['sendTo']
                            , 'cc' => $sendByMan[0]['sendCC']
                            , 'bcc' => $sendByMan[0]['sendBCC']
                          ]];
      $this->sendMail($recipient['man'], $data, $sendByMan[0]['id']);
    }

    News::where([['id', '=', $id]])
          ->update([
              'statusSending' => 1
          ]);

    return response()->json($this->response);
  }
}
