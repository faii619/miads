<?php

namespace App\Http\Controllers\authority_control;

use App\Models\authority_control\AuthorityControl;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ExpertiseController extends BaseController
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
        $result->areaOfExpertise = $request->areaOfExpertise;
        $result->save();
        return response()->json($this->response); 
      }


}
