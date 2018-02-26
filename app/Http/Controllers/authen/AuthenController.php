<?php

namespace App\Http\Controllers\authen;

use App\Models\user_login\UserLogin;
use App\Models\person\Person;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthenController extends BaseController
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

    public function authen(Request $request)
    {
        $username = $request->username;
        $passwords = $request->passwords;
        $user_profile = array('authen_status'=>0);
        $query = [
            ['login', '=', $username],
            ['password', '=', md5($passwords)],
            ['status', '=', 1],
        ];

        $result = UserLogin::where($query)->first();

        if (!empty($result)) {
          $user_profile['authen_status'] = 1;
          $user_profile['login'] = $result['login'];
          $user_profile['token'] = md5('ttech-innovation@miads2017');
          $user_profile['person_id'] = $result['personId'];

          if ($result['personId'] != null) {
            $person_profile = $this->get_person_profile($result['personId']);
            $user_profile['name'] = $person_profile['name'];
          }

        }

        return response()->json($user_profile);
    }

    private function get_person_profile($person_id)
    {
      $result = Person::find($person_id);
      return $result;
    }

}
