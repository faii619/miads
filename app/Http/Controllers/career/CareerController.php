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

  public function count_person()
  {
    $year = Career::where([
      ['status', 1],
      ['startYear', '!=', '']
    ])->groupby('startYear')->get(['Career.startYear']);
    
    $results['lineLabels'] = array();
    $results['lineData'] = array();

    foreach ($year as $key => $value) {
      array_push($results['lineLabels'], $value['startYear']);
      $count = $this->count_person_by_startYear($value['startYear']);
      array_push($results['lineData'], $count);
    }

    return response()->json($results);
  }

  private function count_person_by_startYear($year)
  {
    $results = Career::where('startYear', $year)->count();
    return $results;
  }

}
