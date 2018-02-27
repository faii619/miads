<?php

namespace App\Http\Controllers\country;

use App\Http\Controllers\ImageController;
use App\Http\Controllers\UploadController;
use App\Models\country\Country;
use App\Models\file\File;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class CountryController extends BaseController
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
    private $path = 'images/country/';

    public function country()
    {
        $items = [
            'AddressCountry.*', 'File.id as FileId', 'File.fileName',
        ];

        $results = Country::where('AddressCountry.status', 1)
            ->leftJoin('File', 'AddressCountry.flagImage', '=', 'File.id')
            ->orderBy('ordinal', 'asc')->take(200)->get($items);

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

        $result->save();
        return response()->json($this->response);
    }

    public function delete($id)
    {
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
