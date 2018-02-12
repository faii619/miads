<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class UploadController extends Controller
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

  public function setImage($request, $path)
  {
    $link_name = 'default.png';

    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $origin_name = $image->getClientOriginalName();
      $link_name = date('YmdHis').'_'.uniqid().'_'.$origin_name;

      $image->move($path, $link_name);
    }
    
    return $link_name;
    // $image_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.$this->dest.$link_name;
    // return response()->json(['imageUrl'=>$image_url]);
  }

}
