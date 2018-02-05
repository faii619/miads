<?php

namespace App\Http\Controllers\alumni;

use App\Models\person\Person;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class AlumniController extends BaseController
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

    public function sort(Request $request)
    {
      if (!empty($request->code)) {
        $arr_where = [['alumni.code', '=', $request->code]];
      } else {
        $arr_where = [
          ['name', '<>', 'null']
          , ['caption', '<>', 'null'],
        ];
      }

      $result = Person::where($arr_where)
                  ->leftJoin('alumni', 'person.id', '=', 'alumni.personId')
                  ->leftJoin('addresscountry', 'person.nationalityAddressCountryId', '=', 'addresscountry.Id')
                  ->orderBy('alumni.code', 'asc')
                  ->get(['person.*', 'alumni.code', 'addresscountry.caption']);

      return response()->json($result);
    }
}
