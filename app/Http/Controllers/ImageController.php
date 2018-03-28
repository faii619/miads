<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class ImageController extends BaseController
{
  public function getImagesUrl($results, $path, $index='image')
  {
    foreach ($results as $key => $value) {
      $image = $this->getImageUrl($value[$index], $path);
      $results[$key][$index.'_url'] = $image;
    }

    return $results;
  }

  public function getImageUrl($image, $path)
  {
    $image = $this->verifyImage($image, $path);
    if ($image =='default.png') {
      $image_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/images/'.$image;
      // $image_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/web/miads-api/public/images/'.$image;
      // $image_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/alumni-api/public/images/'.$image;
    } else {
      $image_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.$path.$image;
      // $image_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/web/miads-api/public/'.$path.$image;
      // $image_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/alumni-api/public/'.$path.$image;
    }
    
    return $image_url;
  }

  private function verifyImage($image, $path)
  {
    $path_url = $_SERVER['DOCUMENT_ROOT'].'/'.$path.$image;
    // $path_url = $_SERVER['DOCUMENT_ROOT'].'/web/miads-api/public/'.$path.$image;
    // $path_url = $_SERVER['DOCUMENT_ROOT'].'/alumni-api/public/'.$path.$image;
    if (!file_exists($path_url) || $image == NULL) $image = 'default.png';
    return $image;
  }
}
