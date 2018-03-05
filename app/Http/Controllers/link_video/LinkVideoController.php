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
        return response()->json($results);
    }

    public function create(Request $request)
    {
        $upload = new UploadController();
        $image = $upload->setImage($request, $this->path);

        $file = new File();
        $file->fileName = $image;
        $file->save();
        $lastIdFile = $file->id;

        $result = new Country;
        $result->caption = $request->caption;
        $result->ordinal = $request->ordinal;
        $result->flagImage = $lastIdFile;

        $result->save();
        return response()->json($this->response);
    }

    public function edit(Request $request)
    {
        $upload = new UploadController();
        $image = $upload->setImage($request, $this->path);

        $file = File::find($request->FileId);
        $file->fileName = $image;
        $file->save();

        $result = Country::find($request->id);
        $result->caption = $request->caption;
        $result->ordinal = $request->ordinal;

        $result->save();
        return response()->json($this->response);
    }

    public function delete($id, $file_id)
    {
        $file = File::find($file_id);
        $file->status = 0;
        $file->save();

        $results = Country::find($id);
        $results->status = 0;
        $results->save();

        return response()->json($this->response);
    }
    public function getCountry($results)
    {
        $results = Country::where('status', 1)->get();
        return $results;
    }

}
