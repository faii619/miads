<?php

namespace App\Http\Controllers\alumni;

use App\Models\alumni\Alumni;
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
}
