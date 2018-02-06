<?php

namespace App\Http\Controllers\alumni;

use App\Models\person\Person;
use App\Models\address\Address;
use App\Models\file\File;
use App\Models\alumni\Alumni;
use App\Models\user_login\UserLogin;
use App\Models\user_login_user_role\UserLoginUserRole;
use App\Models\career\Career;
use App\Models\program_participant\ProgramParticipant;
use Illuminate\Http\Request;
use Illuminate\Hashing\BcryptHasher;
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
      $result[0]['officeContactAddress'] = ($result[0]['isPreferOfficeContact'] = 0) ? $result[0]['homeAddress'] : $result[0]['officeAddress'] ;
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
    $personCode = $request->code;

    $instanceFile = new File;
    $instanceFile->fileName = $request->imageName;
    $instanceFile->fileSize = $request->imageSize;
    $instanceFile->save();
    $fileId = $instanceFile->id;
    
    $instanceHomeAddress = new address;
    $instanceHomeAddress->streetAddress = $request->homeStreetAddress;
    $instanceHomeAddress->city = $request->homeCity;
    $instanceHomeAddress->province = $request->homeProvince;
    $instanceHomeAddress->addressCountryId = $request->homeCountry;
    $instanceHomeAddress->zip = $request->homeZip;
    $instanceHomeAddress->tel = $request->homeTel;
    $instanceHomeAddress->fax = $request->homeFax;
    $instanceHomeAddress->mobile = $request->homeMobile;
    $instanceHomeAddress->save();
    $homeId = $instanceHomeAddress->id;
    
    $instanceOfficeAddress = new address;
    $instanceOfficeAddress->streetAddress = $request->officeStreetAddress;
    $instanceOfficeAddress->city = $request->officeCity;
    $instanceOfficeAddress->province = $request->officeProvince;
    $instanceOfficeAddress->addressCountryId = $request->officeCountry;
    $instanceOfficeAddress->zip = $request->officeZip;
    $instanceOfficeAddress->tel = $request->officeTel;
    $instanceOfficeAddress->fax = $request->officeFax;
    $instanceOfficeAddress->mobile = $request->officeMobile;
    $instanceOfficeAddress->save();
    $officeId = $instanceOfficeAddress->id;
    
    $instancePerson = new Person;
    $instancePerson->personTitleId = $request->title;
    $instancePerson->name = $request->name;
    $instancePerson->birthDate = $request->birthday;
    $instancePerson->email = $request->email;
    $instancePerson->otherEmails = $request->otherEmails;
    $instancePerson->photoFileId = $fileId;
    $instancePerson->homeAddressId = $homeId;
    $instancePerson->officeAddressId = $officeId;
    $instancePerson->isPreferOfficeContact = $request->ContactAddress;
    $instancePerson->gender = $request->gender;
    $instancePerson->nationalityAddressCountryId = $request->nationCountry;
    $instancePerson->save();
    $personId = $instancePerson->id;
    
    $instanceAlumni = new Alumni;
    $instanceAlumni->code = $personCode;
    $instanceAlumni->personId = $personId;
    $instanceAlumni->save();
    
    $instanceCareer = new Career;
    $instanceCareer->position = $request->careerPosition;
    $instanceCareer->startYear = $request->careerStartYear;
    $instanceCareer->areaOfExpertise = $request->careerExpertise;
    $instanceCareer->govMinistryName = $request->careerMinistry;
    $instanceCareer->govDepartmentName = $request->careerDepartment;
    $instanceCareer->organizationName = $request->careerOrganizationName;
    $instanceCareer->organizationDepartment = $request->careerOrganizationDepartment;
    $instanceCareer->universityName = $request->careerUniversityName;
    $instanceCareer->universityDepartment = $request->careerUniversityDepartment;
    $instanceCareer->careerOrganizationTypeId = $request->careerOrganizationType;
    $instanceCareer->personId = $personId;
    $instanceCareer->division = $request->careerDivision;
    $instanceCareer->save();
    
    $instanceUserLogin = new UserLogin;
    $instanceUserLogin->login = $personCode;
    $instanceUserLogin->password = (new BcryptHasher)->make($personCode);
    $instanceUserLogin->isDisabled = 0;
    $instanceUserLogin->personId = $personId;
    $instanceUserLogin->save();
    $UserLoginId = $instanceUserLogin->id;
    
    $instanceUserLoginUserRole = new UserLoginUserRole;
    $instanceUserLoginUserRole->userLoginId = $UserLoginId;
    $instanceUserLoginUserRole->userRoleId = 2;
    $instanceUserLoginUserRole->save();

    return response()->json($this->response);
  }

}
