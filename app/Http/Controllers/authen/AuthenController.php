<?php

namespace App\Http\Controllers\authen;

use App\Http\Controllers\mail\MailController;
use App\Models\user_login\UserLogin;
use App\Models\person\Person;
use App\Models\alumni\Alumni;
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

    public function forget_password(Request $request)
    {
        $response = array('status' => 0, 'message' => 'No email.');

        $result = Person::where('email', $request->email)
                    ->leftJoin('PersonTitle', 'Person.personTitleId', '=', 'PersonTitle.id')
                    ->leftJoin('Alumni', 'Person.id', '=', 'Alumni.personId')
                    ->get();

        if (count($result) != 0) {
            $gen_password = $this->randomString();

            UserLogin::where([['personId', '=', $result[0]['personId']]])
                ->update([
                    'password' => md5($gen_password)
                ]);

            $data = array(
                            'email' => $request->email
                            , 'email_cc' => 0
                            , 'email_bcc' => 0
                            , 'subject' => 'Your username and new password.'
                            , 'body' => "To. ".$result[0]['caption']." ".$result[0]['name']."<br>This your username and password for login <br>Username: ".$result[0]['code']."<br>Password: ".$gen_password
                        );
            
            $email = new MailController;
            $email->send_email($data);
            
            $response = array('status' => 1, 'message' => 'success');
        }
        return response()->json($response);
    }

    function randomString()
    {
        $str = "";
        $characters = array_merge(range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < 5; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

}
