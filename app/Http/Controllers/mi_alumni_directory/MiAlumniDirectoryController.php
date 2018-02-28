<?php

namespace App\Http\Controllers\mi_alumni_directory;

use App\Models\mi_alumni_directory\MiAlumniDirectory;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class MiAlumniDirectoryController extends BaseController {
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
  
  public function mi_alumni_directory()
  {
    $results = MiAlumniDirectory::all();
    return response()->json($results);
  }
  
  public function edit(Request $request) 
  {
    $results = MiAlumniDirectory::find(0);
    $results->content = $request->content;
    $results->save();
    return response()->json($this->response);
  }
}
