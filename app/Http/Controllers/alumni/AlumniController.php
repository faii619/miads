<?php

namespace App\Http\Controllers\alumni;

use App\Http\Controllers\ImageController;
use App\Http\Controllers\UploadController;
use App\Models\address\Address;
use App\Models\alumni\Alumni;
use App\Models\career\Career;
use App\Models\file\File;
use App\Models\person\Person;
use App\Models\program_participant\ProgramParticipant;
use App\Models\user_login\UserLogin;
use App\Models\user_login_user_role\UserLoginUserRole;
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
    private $path = 'images/alumni/';

    public function sort(Request $request)
    {
        $items = [
            'Person.*'
            , 'Alumni.code'
            , 'Alumni.id as alumniId'
            , 'AddressCountry.*'
            , 'Person.id as personId'
            , 'File.fileName',
        ];

        $conditions[] = ['Person.personStatus', '=', 1];
        $conditions[] = ['Alumni.personId', '!=', 0];

        if ($request->txt_code != '0') {
            $conditions[] = ['Alumni.code', 'like', $request->txt_code . '%'];
        }

        if ($request->txt_name != '0') {
            $conditions[] = ['Person.name', 'like', $request->txt_name . '%'];
        }

        if ($request->txt_email != '0') {
            $conditions[] = ['Person.email', 'like', $request->txt_email . '%'];
        }

        if ($request->countryId != '0') {
            $conditions[] = ['Person.nationalityAddressCountryId', '=', $request->countryId];
        }

        if ($request->txt_year != '0') {
            $conditions[] = ['Career.startYear', '=', $request->txt_year];
        }

        if ($request->programId != '0') {
            $conditions[] = ['ProgramParticipant.programId', '=', $request->programId];
        }

        $result = Person::where($conditions)
            ->leftJoin('Alumni', 'Person.id', '=', 'Alumni.personId')
            ->leftJoin('AddressCountry', 'Person.nationalityAddressCountryId', '=', 'AddressCountry.Id')
            ->leftJoin('File', 'Person.photoFileId', '=', 'File.id')
            ->leftJoin('Career', 'Person.id', '=', 'Career.personId')
            ->leftJoin('ProgramParticipant', 'Person.id', '=', 'ProgramParticipant.alumniId')
            ->orderBy('Person.id', 'desc')
            ->get($items);

        if ($request->txt_code == '0' && $request->txt_name == '0' && $request->txt_email == '0' && $request->countryId == '0' && $request->txt_year == '0' && $request->programId == '0') {
            $result = $result->values()->slice(0, 500);
        }

        $result = $this->getCountryImage($result);

        $images = new ImageController();
        $result = $images->getImagesUrl($result, $this->path, 'fileName');

        return response()->json($result);
    }

    public function getCountryImage($result)
    {
        foreach ($result as $key => $value) {
            $flag = File::find($value['flagImage']);
            $result[$key]['flag'] = $flag['fileName'];
        }

        $images = new ImageController();
        $result = $images->getImagesUrl($result, 'images/country/', 'flag');
        return $result;
    }

    public function alumni(Request $request)
    {
        $items = [
            'Person.*'
            , 'Alumni.code'
            , 'Alumni.id as alumniId'
            , 'AddressCountry.*'
            , 'Person.id as personId'
            , 'File.fileName',
        ];

        $result = Person::where([
            ['Person.personStatus', '=', 1],
            ['Alumni.personId', '!=', 0],
        ])
            ->leftJoin('Alumni', 'Person.id', '=', 'Alumni.personId')
            ->leftJoin('AddressCountry', 'Person.nationalityAddressCountryId', '=', 'AddressCountry.Id')
            ->leftJoin('File', 'Person.photoFileId', '=', 'File.id')
            ->leftJoin('Career', 'Person.id', '=', 'Career.personId')
            ->leftJoin('ProgramParticipant', 'Person.id', '=', 'ProgramParticipant.alumniId')
            ->orderBy('Person.id', 'desc')
            ->take(500)
            ->get($items);

        $result = $this->getCountryImage($result);

        $images = new ImageController();
        // $result = $images->getImagesUrl($result, 'images/country/', 'flagImage');
        $result = $images->getImagesUrl($result, $this->path, 'fileName');

        return response()->json($result);
    }

    public function find($id)
    {
        $item = [
            'Person.*', 'Person.id as personID'
            , 'Alumni.code'
            , 'File.fileName', 'File.fileSize'
            , 'PersonTitle.caption as personTitle'
            , 'AddressCountry.caption as nationality'
            , 'AddressCountry.flagImage as flagImage'
            , 'Career.*'
            , 'Gender.caption as personGender'
            , 'CareerOrganizationType.caption as organizationType',
        ];

        $result = Person::where([['Person.id', '=', $id]])
            ->leftJoin('Alumni', 'Person.id', '=', 'Alumni.personId')
            ->leftJoin('PersonTitle', 'Person.personTitleId', '=', 'PersonTitle.id')
            ->leftJoin('Gender', 'Person.gender', '=', 'Gender.id')
            ->leftJoin('File', 'Person.photoFileId', '=', 'File.id')
            ->leftJoin('AddressCountry', 'Person.nationalityAddressCountryId', '=', 'AddressCountry.id')
            ->leftJoin('Career', 'Person.id', '=', 'Career.personId')
            ->leftJoin('CareerOrganizationType', 'Career.careerOrganizationTypeId', '=', 'CareerOrganizationType.id')
            ->get($item);

        if (count($result) > 0) {
            $result[0]['contactAddress'] = $this->text_status;
            $result[0]['officeContactAddress'] = $this->text_status;

            if ($result[0]['isPreferOfficeContact'] != null) {
                $result[0]['contactAddress'] = ($result[0]['isPreferOfficeContact'] == 0) ? 'Home' : 'Office';
                $result[0]['officeContactAddress'] = ($result[0]['isPreferOfficeContact'] == 0) ? $result[0]['homeAddress'] : $result[0]['officeAddress'];
            }

            $result[0]['birthDate'] = $this->getDateShow($result[0]['birthDate']);
            $result[0]['isContact'] = $result[0]['isPreferOfficeContact'];
            $result[0]['homeAddress'] = ($result[0]['homeAddressId'] > 0 && $result[0]['homeAddressId'] != null) ? $this->get_person_address($result[0]['homeAddressId']) : $this->text_status;
            $result[0]['officeAddress'] = ($result[0]['officeAddressId'] > 0 && $result[0]['officeAddressId'] != null) ? $this->get_person_address($result[0]['officeAddressId']) : $this->text_status;
            $result[0]['program'] = $this->get_person_program($id);
            $result[0]['program'] = $this->editFormatDate($result[0]['program']);

            $images = new ImageController();
            // $result = $images->getImagesUrl($result, 'images/country/', 'flagImage');
            $result = $images->getImagesUrl($result, $this->path, 'fileName');
            $result = $this->getCountryImage($result);
        }

        return response()->json($result);
    }

    public function editFormatDate($results)
    {
        foreach ($results as $key => $value) {
            $start = $this->getDateShow($value['startDate']);
            $end = $this->getDateShow($value['endDate']);
            $results[$key]['schedule'] = $start . " - " . $end;
        }
        return $results;
    }

    public function getDateShow($date)
    {
        $formatDate = '00/00/0000';
        if ($date !== null) {
            $bd = explode("-", $date);
            $formatDate = $bd[2] . '/' . $bd[1] . '/' . $bd[0];
        }
        return $formatDate;
    }

    public function get_person_address($address_id)
    {
        $result = Address::where([['Address.id', '=', $address_id]])
            ->leftJoin('AddressCountry', 'Address.addressCountryId', '=', 'AddressCountry.id')
            ->get(['AddressCountry.*', 'Address.*']);

        return $result;
    }

    public function get_person_program($person_id)
    {
        $result = ProgramParticipant::where([['alumniId', '=', $person_id]])
            ->leftJoin('Program', 'ProgramParticipant.programId', '=', 'Program.id')
            ->get(['Program.*']);
        return $result;
    }

    public function create(Request $request)
    {
        $image = 'default.png';
        $birthday = '0000-00-00';
        $personCode = $request->code;

        if ($request->birthday != 0) {
            $bd = explode("/", $request->birthday);
            $birthday = $bd[2] . '-' . $bd[1] . '-' . $bd[0];
        }

        $upload = new UploadController();
        $image = $upload->setImage($request, $this->path);

        $instanceFile = new File;
        $instanceFile->fileName = $image;
        $instanceFile->fileSize = $request->imageSize;
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
        $instancePerson->birthDate = $birthday;
        $instancePerson->email = $request->email;
        $instancePerson->otherEmails = $request->otherEmails;
        $instancePerson->facebook = $request->facebook;
        $instancePerson->twitter = $request->twitter;
        $instancePerson->linkedIn = $request->linked_in;
        $instancePerson->line = $request->line;
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
        $instanceCareer->startYear = ($request->careerStartYear != 0 ? $request->careerStartYear : date('Y'));
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
        $image = $request->image;
        $birthday = '0000-00-00';

        if ($request->birthday != 0) {
            $bd = explode("/", $request->birthday);
            $birthday = $bd[2] . '-' . $bd[1] . '-' . $bd[0];
        }

        $upload = new UploadController();
        $image = $upload->setImage($request, $this->path);

        $resultPerson = Person::find($personId);
        $resultPerson->personTitleId = $request->title;
        $resultPerson->name = $request->name;
        $resultPerson->birthDate = $birthday;
        $resultPerson->email = $request->email;
        $resultPerson->otherEmails = $request->otherEmails;
        $resultPerson->facebook = $request->facebook;
        $resultPerson->twitter = $request->twitter;
        $resultPerson->linkedIn = $request->linked_in;
        $resultPerson->line = $request->line;
        $resultPerson->isPreferOfficeContact = $request->ContactAddress;
        $resultPerson->gender = $request->gender;
        $resultPerson->nationalityAddressCountryId = $request->nationCountry;
        $resultPerson->save();

        Alumni::where([['personId', '=', $personId]])
            ->update([
                'code' => $personCode,
            ]);

        UserLogin::where([['personId', '=', $personId]])
            ->update([
                'login' => $personCode,
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
                , 'division' => $request->careerDivision,
            ]);

        $resultFile = File::find($fileId);
        $resultFile->fileName = $image;
        $resultFile->fileSize = $request->imageSize;
        $resultFile->save();

        return response()->json($this->response);
    }

    public function delete($id)
    {
        $status = ['status' => 0];
        $resultPerson = Person::find($id);
        $resultPerson->personStatus = 0;
        $resultPerson->save();

        File::where('Person.id', $id)
            ->leftJoin('Person', 'File.id', '=', 'Person.photoFileId')
            ->update($status);

        Alumni::where([['personId', '=', $id]])->update($status);
        UserLogin::where([['personId', '=', $id]])->update($status);
        Career::where([['personId', '=', $id]])->update($status);

        return response()->json($this->response);
    }

    public function latest($rows)
    {
        $result = Person::where('personStatus', 1)
            ->leftJoin('Alumni', 'Person.id', '=', 'Alumni.personId')
            ->leftJoin('AddressCountry', 'Person.nationalityAddressCountryId', '=', 'AddressCountry.Id')
            ->leftJoin('File', 'Person.photoFileId', '=', 'File.id')
            ->orderBy('Person.id', 'desc')
            ->limit($rows)
            ->get(['Person.*', 'Alumni.code', 'AddressCountry.caption', 'File.fileName', 'AddressCountry.flagImage']);

        $result = $this->getCountryImage($result);
        
        $images = new ImageController();
        $result = $images->getImagesUrl($result, $this->path, 'fileName');
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
                    'password' => md5($new),
                ]);
        } else {
            $this->response = ['status' => 0, 'message' => 'not match'];
        }
        return response()->json($this->response);
    }

    public function count_person()
    {
        $results = Person::where([['personStatus', '=', '1']])
                        ->count();

        return response()->json($results);
    }

}
