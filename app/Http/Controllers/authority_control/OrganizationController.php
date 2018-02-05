<?php

namespace App\Http\Controllers\authority_control;

use App\Models\authority_control\AuthorityControl;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class OrganizationController extends BaseController
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
        $result->organizationName = $request->organizationName;
        $result->save();
        return response()->json($this->response); 
      }
      public function create_department(Request $request){
        $result = new AuthorityControl;
        $result->organizationDepartment = $request->organizationDepartment;
        $result->save();
        return response()->json($this->response); 
      }

}
