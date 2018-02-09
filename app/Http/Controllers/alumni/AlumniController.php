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
    $conditions[] = ['Person.personStatus', '=', 1];
    
    if ($request->txt_code !='0') {
      $conditions[] = ['Alumni.code', 'like', $request->txt_code.'%'];
    }

    if ($request->txt_name !='0') {
      $conditions[] = ['Person.name', 'like', $request->txt_name.'%'];
    }

    if ($request->txt_email !='0') {
      $conditions[] = ['Person.email', 'like', $request->txt_email.'%'];
    }

    if ($request->countryId !='0') {
      $conditions[] = ['Person.nationalityAddressCountryId', '=', $request->countryId];
    }

    $result = Person::where($conditions)
    ->leftJoin('Alumni', 'Person.id', '=', 'Alumni.personId')
    ->leftJoin('AddressCountry', 'Person.nationalityAddressCountryId', '=', 'AddressCountry.Id')
    ->orderBy('Alumni.code', 'asc')
    // ->take(20)
    ->get(['Person.*', 'Alumni.code', 'AddressCountry.caption']);

    return response()->json($result);
  }

  public function find($id)
  {
    $item = [
      'person.*', 'alumni.code', 'file.fileName'
      , 'persontitle.caption as personTitle'
      , 'addresscountry.caption as nationality'
      , 'career.*'
    ];

    $result = Person::where([['person.id', '=', $id]])
                ->leftJoin('alumni', 'person.id', '=', 'alumni.personId')
                ->leftJoin('persontitle', 'person.personTitleId', '=', 'persontitle.id')
                ->leftJoin('file', 'person.photoFileId', '=', 'file.id')
                ->leftJoin('addresscountry', 'person.nationalityAddressCountryId', '=', 'addresscountry.id')
                ->leftJoin('career', 'person.id', 'career.personId')
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
    $imageName = (!empty($request->imageName) && $request->imageName != '') ? $request->imageName : 'default.jpg' ;
    $imageSize = (!empty($request->imageSize) && $request->imageSize != '') ? $request->imageSize : '0' ;

    $instanceFile = new File;
    $instanceFile->fileName = $imageName;
    $instanceFile->fileSize = $imageSize;
    $instanceFile->save();
    $fileId = $instanceFile->id;
    
    $instanceHomeAddress = new Address;
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
    
    $instanceOfficeAddress = new Address;
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
    $instanceUserLogin->password = md5($personCode);
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

  public function edit(Request $request)
  {
    $personId = $request->id;
    $personCode = $request->code;
    $fileId = $request->fileId;
    $homeId = $request->homeId;
    $officeId = $request->officeId;

    $resultPerson = Person::find($personId);
    $resultPerson->personTitleId = $request->title;
    $resultPerson->name = $request->name;
    $resultPerson->birthDate = $request->birthday;
    $resultPerson->email = $request->email;
    $resultPerson->otherEmails = $request->otherEmails;
    $resultPerson->photoFileId = $fileId;
    $resultPerson->homeAddressId = $homeId;
    $resultPerson->officeAddressId = $officeId;
    $resultPerson->isPreferOfficeContact = $request->ContactAddress;
    $resultPerson->gender = $request->gender;
    $resultPerson->nationalityAddressCountryId = $request->nationCountry;
    $resultPerson->save();

    $resultFile = File::find($fileId);
    $resultFile->fileName = $request->imageName;
    $resultFile->fileSize = $request->imageSize;
    $resultFile->save();
    
    Alumni::where([['personId', '=', $personId]])
            ->update([
                'code' => $personCode
              ]);

    UserLogin::where([['personId', '=', $personId]])
              ->update([
                  'login' => $personCode
                ]);
    
    $resultHomeAddress = Address::find($homeId);
    $resultHomeAddress->streetAddress = $request->homeStreetAddress;
    $resultHomeAddress->city = $request->homeCity;
    $resultHomeAddress->province = $request->homeProvince;
    $resultHomeAddress->addressCountryId = $request->homeCountry;
    $resultHomeAddress->zip = $request->homeZip;
    $resultHomeAddress->tel = $request->homeTel;
    $resultHomeAddress->fax = $request->homeFax;
    $resultHomeAddress->mobile = $request->homeMobile;
    $resultHomeAddress->save();
    
    $resultOfficeAddress = Address::find($officeId);
    $resultOfficeAddress->streetAddress = $request->officeStreetAddress;
    $resultOfficeAddress->city = $request->officeCity;
    $resultOfficeAddress->province = $request->officeProvince;
    $resultOfficeAddress->addressCountryId = $request->officeCountry;
    $resultOfficeAddress->zip = $request->officeZip;
    $resultOfficeAddress->tel = $request->officeTel;
    $resultOfficeAddress->fax = $request->officeFax;
    $resultOfficeAddress->mobile = $request->officeMobile;
    $resultOfficeAddress->save();
    
    Career::where([['personId', '=', $personId]])
            ->update([
              'position' => $request->careerPosition
              , 'startYear' => $request->careerStartYear
              , 'areaOfExpertise' => $request->careerExpertise
              , 'govMinistryName' => $request->careerMinistry
              , 'govDepartmentName' => $request->careerDepartment
              , 'organizationName' => $request->careerOrganizationName
              , 'organizationDepartment' => $request->careerOrganizationDepartment
              , 'universityName' => $request->careerUniversityName
              , 'universityDepartment' => $request->careerUniversityDepartment
              , 'careerOrganizationTypeId' => $request->careerOrganizationType
              , 'division' => $request->careerDivision
            ]);

    return response()->json($this->response);
  }

  public function delete($id)
  {
    $result = $this->find($id)->original;
    $personId = $result[0]['personId'];
    $fileId = $result[0]['photoFileId'];
    $status = ['status' => 0];

    $resultPerson = Person::find($personId);
    $resultPerson->personStatus = 0;
    $resultPerson->save();

    $resultFile = File::find($fileId);
    $resultFile->status = 0;
    $resultFile->save();
    
    Alumni::where([['personId', '=', $personId]])->update($status);

    UserLogin::where([['personId', '=', $personId]])->update($status);
    
    Career::where([['personId', '=', $id]])->update($status);

    return response()->json($this->response);
  }

  public function latest()
  {
    $result = Person::where('personStatus', 1)
                ->leftJoin('alumni', 'person.id', '=', 'alumni.personId')
                ->leftJoin('addresscountry', 'person.nationalityAddressCountryId', '=', 'addresscountry.Id')
                ->orderBy('person.id', 'desc')
                ->limit(20)
                ->get(['person.*', 'alumni.code', 'addresscountry.caption']);

    return response()->json($result);
  }

  public function change_passwod(Request $request)
  {
    $personId = $request->id;
    $new = $request->newPassword;
    $confirm = $request->confirmPassword;
    if ($new == $confirm) {
      UserLogin::where([['personId', '=', $personId]])
                ->update([
                    'password' => md5($new)
                  ]);
    } else {
      $this->response = ['status' => 0, 'message' => 'not match'];
    }
    return response()->json($this->response);
  }

}
