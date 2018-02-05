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

$router->get('/program_depa', 'program_department\ProgramDepartmentController@program_department');

$router->get('/ministry', 'authority_control\MinistryController@create');

$router->get('/country', 'country\CountryController@country');
$router->post('/country/create', 'country\CountryController@create');
$router->post('/country/edit', 'country\CountryController@edit');
$router->delete('/country/delete/{id:[0-9]+}', 'country\CountryController@delete');

$router->post('/ministry/create', 'authority_control\MinistryController@create');
$router->post('/ministry_department/create', 'authority_control\MinistryController@create_department');
$router->post('/university_department/create', 'authority_control\UniversityController@create_department');
$router->post('/organization/create', 'authority_control\OrganizationController@create');
$router->post('/organization_department/create', 'authority_control\OrganizationController@create_department');
$router->post('/job_position/create', 'authority_control\PositionController@create');
$router->post('/expertise/create', 'authority_control\ExpertiseController@create');
$router->post('/division/create', 'authority_control\DivisionController@create');

// $router->get('/program_depa/{id:[0-9]+}', 'program_department\ProgramDepartmentController@program_department');
$router->get('/program_depa', 'program_department\ProgramDepartmentController@program_department');
// $router->post('/program_depa/create', 'program_department\ProgramDepartmentController@create');
// $router->post('/program_depa/edit', 'program_department\ProgramDepartmentController@edit');
// $router->delete('/program_depa/delete', 'program_department\ProgramDepartmentController@delete');
$router->get('/ministry', 'authority_control\MinistryController@create');

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

<<<<<<< HEAD
$router->get('/news_cate', 'news_category\NewsCategoryController@news_cate');
$router->post('/news_cate/create', 'news_category\NewsCategoryController@edit');
=======
$router->post('/news_cate/create', 'news_category\NewsCategoryController@create');
>>>>>>> 70f752f343310f7955877c50e74810fe5b42a7a7
$router->post('/news_cate/edit', 'news_category\NewsCategoryController@edit');
$router->delete('/news_cate/delete/{id:[0-9]+}', 'news_category\NewsCategoryController@delete');

$router->post('/alumni/sort', 'alumni\AlumniController@sort');

