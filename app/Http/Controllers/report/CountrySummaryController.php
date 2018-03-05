<?php

namespace App\Http\Controllers\report;

// use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Controllers\ImageController;
use App\Models\country\Country;
use App\Models\person\Person;
use App\Models\file\File;
use Illuminate\Support\Collection;
use Laravel\Lumen\Routing\Controller as BaseController;

class CountrySummaryController extends BaseController
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
    private $path = 'images/country/';

    public function genders_by_country_id()
    {
        $results['results'] = Country::where('AddressCountry.status', 1)
            ->orderBy('caption', 'asc')
            ->get();

        $results['total_count_male'] = 0;
        $results['total_count_female'] = 0;
        $results['total_count_undefined'] = 0;
        $results['total_count_all'] = 0;

        foreach ($results['results'] as $key => $value) {
            $results['results'][$key]['count_male'] = $this->count_gender_by_gender_id_and_country_id(['status' => 1, 'gender_id' => 1, 'country_id' => $value['id']]);
            $results['results'][$key]['count_female'] = $this->count_gender_by_gender_id_and_country_id(['status' => 1, 'gender_id' => 0, 'country_id' => $value['id']]);
            $results['results'][$key]['count_undefine'] = $this->count_gender_by_gender_id_and_country_id(['status' => 1, 'gender_id' => null, 'country_id' => $value['id']]);
            $count_total = ($results['results'][$key]['count_male'] + $results['results'][$key]['count_female'] + $results['results'][$key]['count_undefine']);

            $results['results'][$key]['count_total'] = $count_total;
            $results['total_count_male'] += $results['results'][$key]['count_male'];
            $results['total_count_female'] += $results['results'][$key]['count_female'];
            $results['total_count_undefined'] += $results['results'][$key]['count_undefine'];

            $flag = File::find($value['flagImage']);
            $results['results'][$key]['flagImage'] = $flag['fileName'];
        }

        $images = new ImageController();
        $results['results'] = $images->getImagesUrl($results['results'], $this->path, 'flagImage');

        $results['total_count_all'] = $results['total_count_male'] + $results['total_count_female'] + $results['total_count_undefined'];

        return response()->json($results);
    }

    public function country_summary_by_country_id()
    {
        $results = Country::where('AddressCountry.status', 1)
                            ->get();

        foreach ($results as $key => $value) {
            $results[$key]['participants_count'] = $this->count_country_by_country_id($value['id']);

            $flag = File::find($value['flagImage']);
            $results[$key]['image'] = $flag['fileName'];
        }

        $images = new ImageController();
        $results = $images->getImagesUrl($results, $this->path, 'image');

        $results = $results->sortByDesc('participants_count')->values()->slice(0, 5);
        return response()->json($results);
    }

    public function count_genders()
    {
        $genders = array(
            array('id' => null, 'name' => 'Undefined'),
            array('id' => 0, 'name' => 'Female'),
            array('id' => 1, 'name' => 'Male'),
        );

        $results['labels'] = array();
        $results['data'] = array();

        foreach ($genders as $key => $value) {
            array_push($results['labels'], $value['name']);
            $count = $this->count_gender_by_gender_id($value['id']);
            array_push($results['data'], $count);
        }

        return response()->json($results);
    }

    private function count_country_by_country_id($id)
    {
        $results = Person::where('Person.personStatus', 1)
            ->where('Person.nationalityAddressCountryId', $id)
            ->count();
        return $results;
    }

    private function count_gender_by_gender_id($gender)
    {
        $results = Person::where('Person.personStatus', 1)
            ->where('Person.gender', $gender)
            ->count();

        return $results;
    }

    public function count_gender_by_gender_id_and_country_id($query)
    {
        $results = Person::where([
            ['Person.personStatus', '=', $query['status']],
            ['Person.gender', '=', $query['gender_id']],
            ['Person.nationalityAddressCountryId', '=', $query['country_id']],
        ])
            ->count();

        return $results;
    }

    public function count_courtry()
    {
        $results = Country::where('AddressCountry.status', 1)->count();
  
        return response()->json($results);
    }

    public function count_alumni_country()
    {
        $conditions = [15, 16, 17, 18, 20, 21];
        $results = Country::whereIn('id', $conditions)->get();

        foreach ($results as $key => $value) {
            $results[$key]['participants_count'] = $this->count_country_by_country_id($value['id']);

            $images = new ImageController();
            $results[$key]['image_url'] = $images->getImageUrl($value['flagImage'], $this->path);
        }
  
        return response()->json($results);
    }
}
