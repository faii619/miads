<?php

namespace App\Http\Controllers\news_subscription;

use App\Models\news_subscription\NewsSubscription;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class NewsSubscriptionController extends BaseController
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

    public function news_subscription($id)
    {
      $results = NewsSubscription::where('newsCategoryId', $id)
        ->where('NewsSubscription.status', 1)
        ->leftJoin('Person', 'NewsSubscription.personId', '=', 'Person.id')
        ->leftJoin('Alumni', 'Person.id', '=', 'Alumni.personId')
        ->leftJoin('AddressCountry', 'Person.nationalityAddressCountryId', '=', 'AddressCountry.id')
        ->get(['NewsSubscription.*', 'Alumni.code', 'Person.name', 'Person.email', 'AddressCountry.caption']);
      return response()->json($results);
    }

    public function subscribe(Request $request)
    {
      $alumni = $request->alumni;
      foreach ($alumni as $key => $value) {
        $rows = $this->verify_alumni_registered($value, $request->newsCategoryId);
        if ($rows <= 0) {
          $results = new NewsSubscription;
          $results->newsCategoryId = $request->newsCategoryId;
          $results->personId = $value;
          $results->status = 1;
          $results->save();
        } else {
          $condition = [
                        ['newsCategoryId', '=', $request->newsCategoryId]
                        , ['personId', '=', $value]
                      ];
          NewsSubscription::where($condition)
                ->update([
                    'status' => 1
                ]);
        }
      }
      return response()->json($this->response);
    }

    private function verify_alumni_registered($personId, $newsCategoryId)
    {
      $results = NewsSubscription::where([
        ['personId', '=', $personId],
        ['newsCategoryId', '=', $newsCategoryId],
      ])->count();
      return $results;
    }

    public function delete(Request $request)
    {
      $results = NewsSubscription::where('newsCategoryId', $request->newsCategoryId)
        ->where('personId', $request->personId)
        ->update(['status' => 0]);
      return response()->json($this->response);
    }
}
