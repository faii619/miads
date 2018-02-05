<?php

namespace App\Http\Controllers\alumni;

use App\Models\person\Person;
use App\Models\address\Address;
use App\Models\program_participant\ProgramParticipant;
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
        , ['caption', '<>', 'null']
      ];
    }

    $result = Person::where($arr_where)
                ->leftJoin('alumni', 'person.id', '=', 'alumni.personId')
                ->leftJoin('addresscountry', 'person.nationalityAddressCountryId', '=', 'addresscountry.Id')
                ->orderBy('alumni.code', 'asc')
                ->get(['person.*', 'alumni.code', 'addresscountry.caption']);

    return response()->json($result);
  }

  public function find($id)
  {
    $result = Person::where([['person.id', '=', $id]])
                ->leftJoin('alumni', 'person.id', '=', 'alumni.personId')
                ->leftJoin('persontitle', 'person.personTitleId', '=', 'persontitle.id')
                ->leftJoin('file', 'person.photoFileId', '=', 'file.id')
                ->leftJoin('addresscountry', 'person.nationalityAddressCountryId', '=', 'addresscountry.id')
                ->get(['person.*', 'alumni.code', 'persontitle.caption as title', 'file.fileName', 'addresscountry.caption as nationality']);

    $result[0]['homeAddress'] = 'No Information';
    if ($result[0]['homeAddressId'] > 0 && $result[0]['homeAddressId'] != NULL) {
      $home = $this->get_person_address($result[0]['homeAddressId']);
      $result[0]['homeAddress'] = $home->original['address'];
      $result[0]['homeTel'] = $home->original[0]['tel'];
      $result[0]['homeFax'] = $home->original[0]['fax'];
      $result[0]['homeMobile'] = $home->original[0]['mobile'];
    }

    $result[0]['officeAddress'] = 'No Information';
    if ($result[0]['officeAddressId'] > 0 && $result[0]['officeAddressId'] != NULL) {
      $office = $this->get_person_address($result[0]['officeAddressId']);
      $result[0]['officeAddress'] = $office->original['address'];
      $result[0]['officeTel'] = $office->original[0]['tel'];
      $result[0]['officeFax'] = $office->original[0]['fax'];
      $result[0]['officeMobile'] = $office->original[0]['mobile'];
    }
    
    $result[0]['officeContactAddress'] = 'No Information';
    if ($result[0]['isPreferOfficeContact'] > 0 && $result[0]['isPreferOfficeContact'] != NULL) {
      $contact = $this->get_person_address($result[0]['isPreferOfficeContact']);
      $result[0]['officeContactAddress'] = $contact->original['address'];
      $result[0]['officeContactTel'] = $contact->original[0]['tel'];
      $result[0]['officeContactFax'] = $contact->original[0]['fax'];
      $result[0]['officeContactMobile'] = $contact->original[0]['mobile'];
    }

    $result[0]['program'] = $this->get_person_program($result[0]['id']);

    return response()->json($result);
  }

  public function get_person_address($address_id)
  {
    $result = Address::where([['address.id', '=', $address_id]])
                ->leftJoin('addresscountry', 'address.addressCountryId', '=', 'addresscountry.id')
                ->get(['addresscountry.*', 'address.*']);
    if (!empty($result[0]['streetAddress'])) {
      $caption = ($result[0]['addressCountryId'] != 1) ? $result[0]['caption'] : '' ;
      $result['address'] = $result[0]['streetAddress'].' '.$result[0]['city'].' '.$result[0]['province'].' '.$caption.' '.$result[0]['zip'];
    } else {
      $result['address'] = $result[0]['caption'];
    }
    
    return response()->json($result);
  }

  public function get_person_program($person_id)
  {
    $result = ProgramParticipant::where([['alumniId', '=', $person_id]])
                ->leftJoin('program', 'programparticipant.programId', '=', 'program.id')
                ->get(['program.*']);
    return $result;
  }

}
