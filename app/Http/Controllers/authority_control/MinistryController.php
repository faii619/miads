<?php

namespace App\Http\Controllers\authority_control;

use App\Models\authority_control\AuthorityControl;
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
    public function create(Request $request){
        $result = new AuthorityControl;
        $result->govMinistryName = $request->govMinistryName;
        $result->save();
        return response()->json($this->response); 
      }
      public function department(){
        $results = AuthorityControl::where('status',1)->get();
        return response()->json($results);
      }
      public function create_department(Request $request){
        $result = new AuthorityControl;
        $result->govDepartmentName = $request->govDepartmentName;
        $result->save();
        return response()->json($this->response); 
      }

}
