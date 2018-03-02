<?php

namespace App\Http\Controllers\alumni;

use App\Http\Controllers\ImageController;
use App\Models\alumni\Alumni;
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

        $images = new ImageController();
        $result = $images->getImagesUrl($result, 'images/country/', 'flagImage');
        $result = $images->getImagesUrl($result, $this->path, 'fileName');

        return response()->json($result);
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
}
