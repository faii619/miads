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
  private $text_status = 'No Information';

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
    $item = [
      'person.*', 'alumni.code', 'file.fileName'
      , 'persontitle.caption as title'
      , 'addresscountry.caption as nationality'
    ];
    
    $result = Person::where([['person.id', '=', $id]])
                ->leftJoin('alumni', 'person.id', '=', 'alumni.personId')
                ->leftJoin('persontitle', 'person.personTitleId', '=', 'persontitle.id')
                ->leftJoin('file', 'person.photoFileId', '=', 'file.id')
                ->leftJoin('addresscountry', 'person.nationalityAddressCountryId', '=', 'addresscountry.id')
                ->get($item);

    if (count($result) > 0) {
      $result[0]['homeAddress'] = ($result[0]['homeAddressId'] > 0 && $result[0]['homeAddressId'] != NULL) ? $this->get_person_address($result[0]['homeAddressId']) : $this->text_status ;
      $result[0]['officeAddress'] = ($result[0]['officeAddressId'] > 0 && $result[0]['officeAddressId'] != NULL) ? $this->get_person_address($result[0]['officeAddressId']) : $this->text_status ;
      $result[0]['officeContactAddress'] = ($result[0]['isPreferOfficeContact'] > 0 && $result[0]['isPreferOfficeContact'] != NULL) ? $this->get_person_address($result[0]['isPreferOfficeContact']) : $this->text_status ;
      $result[0]['program'] = $this->get_person_program($result[0]['id']);
    }

    return response()->json($result);
  }

  public function get_person_address($address_id)
  {
    $result = Address::where([['address.id', '=', $address_id]])
                ->leftJoin('addresscountry', 'address.addressCountryId', '=', 'addresscountry.id')
                ->get(['addresscountry.*', 'address.*']);
    
    return $result;
  }

  public function get_person_program($person_id)
  {
    $result = ProgramParticipant::where([['alumniId', '=', $person_id]])
                ->leftJoin('program', 'programparticipant.programId', '=', 'program.id')
                ->get(['program.*']);
    return $result;
  }

}
