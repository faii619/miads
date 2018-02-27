<?php

namespace App\Http\Controllers\program;

use App\Models\program\Programparticipant;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ProgramparticipantController extends BaseController
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

    public function participants($id)
    {
        $results = Programparticipant::where('ProgramParticipant.programId', $id)
            ->where('ProgramParticipant.status', 1)
            ->leftJoin('Program', 'ProgramParticipant.programId', '=', 'Program.id')
            ->leftJoin('Alumni', 'ProgramParticipant.alumniId', '=', 'Alumni.id')
            ->leftJoin('Person', 'Alumni.personId', '=', 'Person.id')
            ->leftJoin('PersonTitle', 'Person.personTitleId', '=', 'PersonTitle.id')
            ->leftJoin('File', 'Person.photoFileId', '=', 'File.id')
            ->leftJoin('Address', 'Person.homeAddressId', '=', 'Address.id')
            ->leftJoin('AddressCountry', 'Person.nationalityAddressCountryId', '=', 'AddressCountry.id')
            ->get();
        return response()->json($results);
    }

    public function enroll(Request $request)
    {
        $alumni = $request->alumni;
        // $alumni = [7202];

        foreach ($alumni as $key => $value) {
            $rows = $this->verify_alumni_registered($value, $request->programId);
            if ($rows <= 0) {
                $results = new Programparticipant;
                $results->programId = $request->programId;
                $results->alumniId = $value;
                $results->status = 1;
                $results->save();
            }
        }

        return response()->json($this->response);
    }

    public function delete(Request $request)
    {
        // return response()->json($request);

        $results = Programparticipant::where('programId', $request->programId)
            ->where('alumniId', $request->alumniId)
            ->update(['status' => 0]);

        return response()->json($this->response);
    }

    private function verify_alumni_registered($alumni_id, $program_id)
    {
        $results = Programparticipant::where([
            ['alumniId', '=', $alumni_id],
            ['programId', '=', $program_id],
        ])->count();

        return $results;
    }

}
