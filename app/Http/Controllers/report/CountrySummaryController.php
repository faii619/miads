<?php

namespace App\Http\Controllers\report;

// use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Controllers\ImageController;
use App\Models\country\Country;
use App\Models\person\Person;
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

    public function country_summary_all()
    {
        $results = Country::where('AddressCountry.status', 1)
            ->limit(1)
            ->get();
        foreach ($results as $key => $value) {
            $results[$key]['countmaleall'] = $this->countmaleall();
            $results[$key]['countfemaleall'] = $this->countfemaleall();
            $results[$key]['countcountryall'] = $this->countcountryall();
            $results[$key]['countundefineall'] = $this->countundefineall();
        }
        return response()->json($results);
    }

    public function genders_by_country_id()
    {
        $results = Country::where('AddressCountry.status', 1)
            ->orderBy('caption', 'asc')
            ->get();

        $images = new ImageController();
        $results = $images->getImagesUrl($results, $this->path, 'flagImage');

        foreach ($results as $key => $value) {
            $results[$key]['count_male'] = $this->count_gender_by_gender_id_and_country_id(['status' => 1, 'gender_id' => 1, 'country_id' => $value['id']]);
            $results[$key]['count_female'] = $this->count_gender_by_gender_id_and_country_id(['status' => 1, 'gender_id' => 0, 'country_id' => $value['id']]);
            $results[$key]['count_undefine'] = $this->count_gender_by_gender_id_and_country_id(['status' => 1, 'gender_id' => null, 'country_id' => $value['id']]);
            // $results[$key]['countfemaleal'] = 999;
            $count_total = ($results[$key]['count_male'] + $results[$key]['count_female'] + $results[$key]['count_undefine']);
            $results[$key]['count_total'] = $count_total;
        }
        return response()->json($results);
    }

    public function country_summary_by_country_id()
    {
        $results = Country::where('AddressCountry.status', 1)->take(20)->get();

        foreach ($results as $key => $value) {
            $results[$key]['participants_count'] = $this->count_country_by_country_id($value['id']);

            $images = new ImageController();
            $results[$key]['image_url'] = $images->getImageUrl($value['flagImage'], $this->path);
        }

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

    // public function country_na()
    // {
    //     $results = Country::where('AddressCountry.status', 1)
    //         ->get(['caption']);
    //     return $results;
    // }

    // public function countcountrycaption()
    // {
    //     $results = Person::where('Person.personStatus', 1)
    //     // ->where('Person.nationalityAddressCountryId', )
    //     // ->orderBy('Person.nationalityAddressCountryId','desc')
    //         ->count();
    //     return $results;
    // }

    // public function countcountryall()
    // {
    //     $results = Person::where('Person.personStatus', 1)
    //         ->where('Person.nationalityAddressCountryId', '>', 0)
    //         ->count();
    //     return $results;

    // }

    // public function countmale($id)
    // {
    //     $results = Person::where('Person.personStatus', 1)
    //         ->where('Person.gender', 1)
    //         ->where('Person.nationalityAddressCountryId', $id)
    //         ->count();
    //     return $results;
    // }

    // public function countfemale($id)
    // {
    //     $results = Person::where('Person.personStatus', 1)
    //         ->where('Person.gender', 0)
    //         ->where('Person.nationalityAddressCountryId', $id)
    //         ->count();
    //     return $results;
    // }

    // public function countundefine($id)
    // {
    //     $results = Person::where('Person.personStatus', 1)
    //         ->where('Person.gender', null)
    //         ->where('Person.nationalityAddressCountryId', $id)
    //         ->count();
    //     return $results;
    // }

    // public function countmaleall()
    // {
    //     $results = Person::where('Person.personStatus', 1)
    //         ->where('Person.gender', 1)
    //         ->count();
    //     return $results;
    // }

    // public function countfemaleall()
    // {
    //     $results = Person::where('Person.personStatus', 1)
    //         ->where('Person.gender', 0)
    //         ->count();
    //     return $results;
    // }

    // public function countundefineall()
    // {
    //     $results = Person::where('Person.personStatus', 1)
    //         ->where('Person.gender', null)
    //         ->count();
    //     return $results;
    // }
}
