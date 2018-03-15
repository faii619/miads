<?php

namespace App\Http\Controllers\alumni;

use App\Http\Controllers\ImageController;
use App\Models\person\Person;
use App\Models\file\File;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class AlumniSearchController extends BaseController
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

    public function search_alumni_by_condition(Request $request)
    {
        // return response()->json($request);
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

        if ($request->code != '0') {
            $conditions[] = ['Alumni.code', 'like', $request->code . '%'];
        }

        if ($request->start_date != '0' && $request->end_date != '0') {
            $conditions[] = ['Career.startYear', '>=', $request->start_date];
            $conditions[] = ['Career.startYear', '<=', $request->end_date];
        }

        if ($request->program_id != '0') {
            $conditions[] = ['ProgramParticipant.programId', '=', $request->program_id];
        }

        if ($request->mi_department != '0') {
            $conditions[] = ['Career.govDepartmentName', 'like', $request->mi_department . '%'];
        }

        if ($request->organize_type_id != '0') {
            $conditions[] = ['Career.careerOrganizationTypeId', '=', $request->organize_type_id];
        }

        if ($request->alumni_organization_name != '0') {
            $conditions[] = ['Career.organizationName', 'like', $request->alumni_organization_name . '%'];
        }

        if ($request->country_id != '0') {
            $conditions[] = ['Person.nationalityAddressCountryId', '=', $request->country_id];
        }
        
        if ($request->alumni_area_of_expertise != '0') {
            $conditions[] = ['Career.areaOfExpertise', 'like', $request->alumni_area_of_expertise . '%'];
        }

        if ($request->name != '0') {
            $conditions[] = ['Person.name', 'like', $request->name . '%'];
        }


        $result = Person::where($conditions)
            ->leftJoin('Alumni', 'Person.id', '=', 'Alumni.personId')
            ->leftJoin('AddressCountry', 'Person.nationalityAddressCountryId', '=', 'AddressCountry.Id')
            ->leftJoin('File', 'Person.photoFileId', '=', 'File.id')
            ->leftJoin('Career', 'Person.id', '=', 'Career.personId')
            ->leftJoin('ProgramParticipant', 'Person.id', '=', 'ProgramParticipant.alumniId')
            ->orderBy('Person.id', 'desc')
            ->get($items);

        if ($request->code == '0' && $request->start_date == '0' && $request->end_date == '0' && $request->program_id == '0' && $request->mi_department == '0' && $request->organize_type_id == '0' && $request->alumni_organization_name == '0' && $request->country_id == '0' && $request->alumni_area_of_expertise == '0' && $request->name == '0') {
            $result = $result->values()->slice(0, 500);
        }

        $result = $this->getCountryImage($result);

        $images = new ImageController();
        // $result = $images->getImagesUrl($result, 'images/country/', 'flagImage');
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
}
