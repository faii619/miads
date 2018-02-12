<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class ImageController extends BaseController
{
  public function getImagesUrl($results, $path, $index='image')
  {
    foreach ($results as $key => $value) {
      // $image = $this->verifyImage($value[$index], $path);
      $image = $this->getImageUrl($value[$index], $path);
      $results[$key][$index.'_url'] = $image;
      // $results[$key][$index.'_url'] = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.$path.$image;
    }

    return $results;
  }

  public function getImageUrl($image, $path)
  {
    $image = $this->verifyImage($image, $path);
    if ($image =='default.png') {
      $image_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/images/'.$image;
    }else {
      $image_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.$path.$image;
    }
    
    return $image_url;
  }

  private function verifyImage($image, $path)
  {
    $path_url = $_SERVER['DOCUMENT_ROOT'].'/'.$path.$image;
    if (!file_exists($path_url)) $image = 'default.png';
    return $image;
  }
}
