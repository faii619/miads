<?php

namespace App\Http\Controllers\authority_control;

use App\Models\career\Career;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class MinistryController extends BaseController
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

    public function ministry() {
        $results = Career::where('status', 1)->get();
        return response()->json($results);
    }

    public function delete($id) {
        $results = Career::find($id);
        $results->status = 0;
        $results->save();
        return response()->json($this->response);
      }

    public function create(Request $request){
        $result = new Career;
        $result->govMinistryName = $request->govMinistryName;
        $result->save();
        return response()->json($this->response); 
      }
      public function create_department(Request $request){
        $result = new Career;
        $result->govDepartmentName = $request->govDepartmentName;
        $result->save();
        return response()->json($this->response); 
      }

}
