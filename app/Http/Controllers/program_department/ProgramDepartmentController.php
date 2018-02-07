<?php

namespace App\Http\Controllers\program_department;

use App\Models\program_department\ProgramDepartment;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ProgramDepartmentController extends BaseController
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

    private $response = array('message' => 'success');

    public function program_department() {
      $results = ProgramDepartment::where('status', 1)->get();
      return response()->json($results);
    }

    public function create(Request $request) {
      $results = new ProgramDepartment;
      $results->caption = $request->caption;
      $results->save();
      return response()->json($this->response);
    }

    public function edit(Request $request) {
      $results = ProgramDepartment::find($request->id);
      $results->caption = $request->caption;
      $results->save();
      return response()->json($this->response);
    }

    public function delete($id) {
      ProgramDepartment::where('id', $id)->delete();
      return response()->json($this->response);
    }
}
