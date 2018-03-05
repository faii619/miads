<?php

namespace App\Http\Controllers\link_video;

use App\Models\link_video\LinkVideo;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class LinkVideoController extends BaseController
{
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

    public function link_video()
    {
        $results = LinkVideo::where('status', 1)->get();
        return response()->json($results);
    }

    public function create(Request $request)
    {
        $result = new LinkVideo;
        $result->title = $request->title;
        $result->url_video = $request->url_video;
        $result->save();

        return response()->json($this->response);
    }

    public function edit(Request $request)
    {
        $result = LinkVideo::find($request->id);
        $result->title = $request->title;
        $result->url_video = $request->url_video;
        $result->save();

        return response()->json($this->response);
    }

    public function delete($id)
    {
        $results = LinkVideo::find($id);
        $results->status = 0;
        $results->save();

        return response()->json($this->response);
    }

}
