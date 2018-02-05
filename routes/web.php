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

$router->get('/country', 'country\CountryController@country');
$router->post('/country2', 'country\CountryController@country2');
$router->post('/country/create', 'country\CountryController@create');
$router->post('/country/edit', 'country\CountryController@edit');
$router->post('/country/delete', 'country\CountryController@delete');

$router->get('/ministry', 'authority_control\MinistryController@create');
$router->get('/country', 'country\CountryController@create');


// $router->get('/program/{id:[0-9]+}', 'program\ProgramController@find');
$router->post('/program/create', 'program\ProgramController@create');
$router->post('/program/edit', 'program\ProgramController@edit');
$router->delete('/program/delete/{id:[0-9]+}', 'program\ProgramController@delete');
$router->get('/program/participants/{id:[0-9]+}', 'program\ProgramparticipantController@participants');
$router->post('/program/enroll', 'program\ProgramparticipantController@enroll');

$router->get('/program_depa', 'program_department\ProgramDepartmentController@program_department');
$router->post('/program_depa/create', 'program_department\ProgramDepartmentController@create');
$router->post('/program_depa/edit', 'program_department\ProgramDepartmentController@edit');
$router->delete('/program_depa/delete/{id:[0-9]+}', 'program_department\ProgramDepartmentController@delete');

$router->post('/news_cate/create', 'news_category\NewsCategoryController@edit');
$router->post('/news_cate/edit', 'news_category\NewsCategoryController@edit');
$router->post('/news_cate/delete', 'news_category\NewsCategoryController@edit');

$router->post('/alumni/sort', 'alumni\AlumniController@sort');

