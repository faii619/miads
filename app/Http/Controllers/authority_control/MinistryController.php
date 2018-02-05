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



    // public function program_department()
    // {
    //     $results = ProgramDepartment::all();
    //     return response()->json($results);
    // }
    

    // public function create(Request $request)
    // {
    //   $instance = new Users;
  
    //   $instance->social_user_id = $request->social_user_id;
    //   $instance->name = $request->name;
    //   $instance->email = $request->email;
    //   $instance->password = $request->password;
    //   $instance->image = $request->image;
    //   $instance->image_small = $request->image;
    //   $instance->remember_token = $request->remember_token;
    //   $instance->status = 1;
    //   $instance->save();
  
    //   return response()->json($this->response);
    // }

    // public function edit(Request $request)
    // {
    //   $inputs = $request->all();
    //   $result = Users::find($inputs['txt_id']);
  
    //   $result->name = $inputs['txt_name'];
    //   $result->email = $inputs['txt_email'];
    //   $result->password = $inputs['txt_password'];
    //   $result->image = $inputs['txt_image'];
    //   $result->remember_token = $inputs['txt_remember_token'];
    //   $result->status = $inputs['txt_status'];
    //   $result->save();
  
    //   return response()->json($this->response);
    // }
}
