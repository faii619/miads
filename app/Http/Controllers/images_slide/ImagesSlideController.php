<?php

namespace App\Http\Controllers\images_slide;

use App\Http\Controllers\ImageController;
use App\Http\Controllers\UploadController;
use App\Models\images_slide\ImagesSlide;
use App\Models\file\File;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ImagesSlideController extends BaseController
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
    private $path = 'images/slide/';

    public function images_slide()
    {
        $items = [
            'ImagesSlide.*', 'File.id as FileId', 'File.fileName',
        ];

        $results = ImagesSlide::where('ImagesSlide.status', 1)
            ->leftJoin('File', 'ImagesSlide.fileId', '=', 'File.id')
            // ->orderBy('caption', 'asc')->get($items);
            ->get($items);

        $images = new ImageController();
        $results = $images->getImagesUrl($results, $this->path, 'fileName');

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

        $result = new ImagesSlide;
        $result->fileId = $lastIdFile;

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

        $result = ImagesSlide::find($request->id);

        $result->save();
        return response()->json($this->response);
    }

    public function delete($id, $file_id)
    {
        $file = File::find($file_id);
        $file->status = 0;
        $file->save();

        $results = ImagesSlide::find($id);
        $results->status = 0;
        $results->save();

        return response()->json($this->response);
    }

}
