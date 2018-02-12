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
    // $link_name = 'default.png';
    $link_name = ($request->old_image =='0'? 'default.png' : $request->old_image);
    
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $origin_name = $image->getClientOriginalName();
      $link_name = date('YmdHis').'_'.uniqid().'_'.$origin_name;

      $image->move($path, $link_name);
    }
    
    return $link_name;
  }

}
