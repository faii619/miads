<?php

namespace App\Http\Controllers\report;

use App\Models\program\Program;
use App\Models\program\Programparticipant;
use App\Models\person\Person;
use App\Http\Controllers\ImageController;
use App\Models\career\Career;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ProgramSummaryController extends BaseController {
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      //
  }

  private $response = array('status'=>1,'message' => 'success');
  private $path = 'images/alumni/';

  public function program_summary() {
    $results = Program::where('Program.status', 1)->get();
    foreach ($results as $key => $value) {
      $results[$key]['count'] = $this->count($value['id']);;
    }
    return response()->json($results);
  }
  public function count($id) {
    $results = Programparticipant::where('ProgramParticipant.status', 1)
    ->where('ProgramParticipant.programId', $id)
    ->count();
    return $results;
  }

  public function find($id) {
    $results = Programparticipant::where('ProgramParticipant.status', 1)
    ->where('ProgramParticipant.programId', $id)
    ->get();
    foreach ($results as $key => $value) {
      $results[$key]['Person'] = $this->getPerson($value['alumniId']);
      if ($results[$key]['Person'][0]['isPreferOfficeContact']==0) {
        $results[$key]['Address'] = $this->getAddressHome($value['alumniId']);
      }
      elseif ($results[$key]['Person'][0]['isPreferOfficeContact']==1) {
        $results[$key]['Address'] = $this->getAddressOffice($value['alumniId']);
      }
      $results[$key]['Career'] = $this->getCareer($value['alumniId']);
    }
    return $results;
  }

  public function getAddressHome($id) {
    $results = Person::where('Person.personStatus', 1)
    ->where('Person.id', $id)
    ->leftJoin('Address', 'Person.homeAddressId', '=', 'Address.id')
    ->get();
    return $results;
  }

  public function getAddressOffice($id) {
    $results = Person::where('Person.personStatus', 1)
    ->where('Person.id', $id)
    ->leftJoin('Address', 'Person.officeAddressId', '=', 'Address.id')
    ->get();
    return $results;
  }

  public function getPerson($id) {
    $results = Person::where('Person.personStatus', 1)
    ->where('Person.id', $id)
    ->leftJoin('Alumni', 'Person.id', '=', 'Alumni.personId')
    ->leftJoin('PersonTitle', 'Person.personTitleId', '=', 'PersonTitle.id')
    ->leftJoin('File', 'Person.photoFileId', '=', 'File.id')
    ->get();
    $images = new ImageController();
    $results = $images->getImagesUrl($results, $this->path, 'fileName');
    return $results;
  }

  public function getCareer($id) {
    $results = Career::where('Career.status', 1)
    ->where('Career.personId', $id)
    ->get();
    return $results;
  }
}
