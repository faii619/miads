<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get('/key', function () use ($router) {
    return str_random(32);
});

$router->get('/ministry', 'authority_control\MinistryController@create');
$router->get('/country', 'country\CountryController@create');


// $router->get('/program_depa/{id:[0-9]+}', 'program_department\ProgramDepartmentController@program_department');
$router->get('/program_depa', 'program_department\ProgramDepartmentController@program_department');
$router->post('/program_depa/create', 'program_department\ProgramDepartmentController@create');
$router->post('/program_depa/edit', 'program_department\ProgramDepartmentController@edit');
$router->delete('/program_depa/delete', 'program_department\ProgramDepartmentController@delete');
