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

  public function create(Request $request)
  {
    $instanceFile = new File;
    $instanceFile->fileName = $request->;
    $instanceFile->fileSize = $request->;
    $instanceFile->save();
    
    $instancePerson = new Person;
    $instancePerson->personTitleId = $request->;
    $instancePerson->name = $request->;
    $instancePerson->birthDate = $request->;
    $instancePerson->email = $request->;
    $instancePerson->otherEmails = $request->;
    $instancePerson->photoFileId = $request->;//tb file->PK
    $instancePerson->homeAddressId = $request->;
    $instancePerson->officeAddressId = $request->;
    $instancePerson->isPreferOfficeContact = $request->;
    $instancePerson->gender = $request->;
    $instancePerson->nationalityAddressCountryId = $request->;
    $instancePerson->save();
    
    $instanceAlumni = new Alumni;
    $instanceAlumni->code = $request->;
    $instanceAlumni->personId = $request->;
    $instanceAlumni->save();
    
    $instanceHomeAddress = new address;
    $instanceHomeAddress->streetAddress = $request->;
    $instanceHomeAddress->city = $request->;
    $instanceHomeAddress->province = $request->;
    $instanceHomeAddress->addressCountryId = $request->;
    $instanceHomeAddress->zip = $request->;
    $instanceHomeAddress->tel = $request->;
    $instanceHomeAddress->fax = $request->;
    $instanceHomeAddress->mobile = $request->;
    $instanceHomeAddress->save();
    
    $instanceOfficeAddress = new address;
    $instanceOfficeAddress->streetAddress = $request->;
    $instanceOfficeAddress->city = $request->;
    $instanceOfficeAddress->province = $request->;
    $instanceOfficeAddress->addressCountryId = $request->;
    $instanceOfficeAddress->zip = $request->;
    $instanceOfficeAddress->tel = $request->;
    $instanceOfficeAddress->fax = $request->;
    $instanceOfficeAddress->mobile = $request->;
    $instanceOfficeAddress->save();
    
    $instanceCareer = new career;
    $instanceCareer->position = $request->;
    $instanceCareer->startYear = $request->;
    $instanceCareer->areaOfExpertise = $request->;
    $instanceCareer->govMinistryName = $request->;
    $instanceCareer->govDepartmentName = $request->;
    $instanceCareer->organizationName = $request->;
    $instanceCareer->organizationDepartment = $request->;
    $instanceCareer->universityName = $request->;
    $instanceCareer->universityDepartment = $request->;
    $instanceCareer->careerOrganizationTypeId = $request->;
    $instanceCareer->personId = $request->;
    $instanceCareer->division = $request->;
    $instanceCareer->save();
    
    $instanceUserLogin = new UserLogin;
    $instanceUserLogin->login = $request->;
    $instanceUserLogin->password = $request->;
    $instanceUserLogin->isDisabled = $request->;
    $instanceUserLogin->personId = $request->;
    $instanceUserLogin->save();
    
    $instanceUserLoginUserRole = new UserLoginUserRole;
    $instanceUserLoginUserRole->userLoginId = $request->;
    $instanceUserLoginUserRole->userRoleId = $request->;
    $instanceUserLoginUserRole->save();

    return response()->json($request);
  }

}
