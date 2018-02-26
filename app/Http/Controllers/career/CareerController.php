<?php

namespace App\Http\Controllers\career;

use App\Models\career\Career;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class CareerController extends BaseController
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

  public function career_year()
  {
    $minYear = Career::where([
                        ['status', '=', '1']
                        , ['startYear', '!=', 'NULL']
                        , ['startYear', '!=', '0']
                        , ['startYear', '>', '1970']
                      ])
                    ->min('startYear');

    $nowYear = date('Y');
    $row = ($nowYear - $minYear)+1;

    for ($i=0; $i < $row; $i++) {
      $result[$i]['year'] = $nowYear--;
    }

    return response()->json($result);
  }

}
