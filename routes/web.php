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

$router->get('/country', 'country\CountryController@country');
$router->post('/country/create', 'country\CountryController@create');
$router->post('/country/edit', 'country\CountryController@edit');
$router->delete('/country/delete/{id:[0-9]+}', 'country\CountryController@delete');

$router->get('/ministry', 'authority_control\MinistryController@ministry');
$router->post('/ministry/create', 'authority_control\MinistryController@create');
$router->post('/ministry/edit', 'authority_control\MinistryController@edit');
$router->delete('/ministry/delete/{id:[0-9]+}', 'authority_control\MinistryController@delete');

$router->get('/ministry_departments', 'authority_control\MinistryDepartmentsController@ministry_departments');
$router->post('/ministry_departments/create', 'authority_control\MinistryDepartmentsController@create');
$router->post('/ministry_departments/edit', 'authority_control\MinistryDepartmentsController@edit');

$router->get('/university_department', 'authority_control\UniversityDepartmentsController@university_department');
$router->post('/university_department/create', 'authority_control\UniversityDepartmentsController@create');
$router->post('/university_department/edit', 'authority_control\UniversityDepartmentsController@edit');

$router->get('/university', 'authority_control\UniversityController@university');
$router->post('/university/create', 'authority_control\UniversityController@create');
$router->post('/university/edit', 'authority_control\UniversityController@edit');
$router->delete('/university/delete/{id:[0-9]+}', 'authority_control\UniversityController@delete');

$router->get('/organization', 'authority_control\OrganizationController@organization');
$router->post('/organization/create', 'authority_control\OrganizationController@create');
$router->post('/organization/edit', 'authority_control\OrganizationController@edit');
$router->delete('/organization/delete/{id:[0-9]+}', 'authority_control\OrganizationController@delete');

$router->get('/organization_department', 'authority_control\OrganizationDepartmentController@organization_department');
$router->post('/organization_department/create', 'authority_control\OrganizationDepartmentController@create');
$router->post('/organization_department/edit', 'authority_control\OrganizationDepartmentController@edit');

$router->get('/job_position', 'authority_control\PositionController@job_position');
$router->post('/job_position/create', 'authority_control\PositionController@create');
$router->post('/job_position/edit', 'authority_control\PositionController@edit');
$router->delete('/job_position/delete/{id:[0-9]+}', 'authority_control\PositionController@delete');

$router->get('/expertise', 'authority_control\ExpertiseController@expertise');
$router->post('/expertise/create', 'authority_control\ExpertiseController@create');
$router->post('/expertise/edit', 'authority_control\ExpertiseController@edit');
$router->delete('/expertise/delete/{id:[0-9]+}', 'authority_control\ExpertiseController@delete');

$router->get('/division', 'authority_control\DivisionController@division');
$router->post('/division/create', 'authority_control\DivisionController@create');
$router->post('/division/edit', 'authority_control\DivisionController@edit');
$router->delete('/division/delete/{id:[0-9]+}', 'authority_control\DivisionController@delete');

$router->post('/program/programs_by_conditions', 'program\ProgramController@programs_by_conditions');
$router->get('/program/{id:[0-9]+}', 'program\ProgramController@find');
$router->post('/program/create', 'program\ProgramController@create');
$router->post('/program/edit', 'program\ProgramController@edit');
$router->delete('/program/delete/{id:[0-9]+}', 'program\ProgramController@delete');

$router->get('/program/participants/{id:[0-9]+}', 'program\ProgramparticipantController@participants');
$router->post('/program/enroll', 'program\ProgramparticipantController@enroll');
$router->post('/program/participants/delete', 'program\ProgramparticipantController@delete');

$router->get('/program_departments', 'program_department\ProgramDepartmentController@program_department');
$router->post('/program_departments/create', 'program_department\ProgramDepartmentController@create');
$router->post('/program_departments/edit', 'program_department\ProgramDepartmentController@edit');
$router->delete('/program_departments/delete/{id:[0-9]+}', 'program_department\ProgramDepartmentController@delete');

$router->get('/news_cate', 'news_category\NewsCategoryController@news_cate');
$router->post('/news_cate/create', 'news_category\NewsCategoryController@create');
$router->post('/news_cate/edit', 'news_category\NewsCategoryController@edit');
$router->delete('/news_cate/delete/{id:[0-9]+}', 'news_category\NewsCategoryController@delete');

$router->post('/alumni/sort', 'alumni\AlumniController@sort');
$router->get('/alumni/{id:[0-9]+}', 'alumni\AlumniController@find');
$router->post('/alumni/create', 'alumni\AlumniController@create');
$router->post('/alumni/edit', 'alumni\AlumniController@edit');
$router->delete('/alumni/delete/{id:[0-9]+}', 'alumni\AlumniController@delete');
$router->get('/alumni/latest', 'alumni\AlumniController@latest');
$router->post('/alumni/change_passwod', 'alumni\AlumniController@change_passwod');

$router->get('/persontitle', 'persontitle\PersonTitleController@person_title');

$router->get('/gender', 'gender\GenderController@gender');

// $router->get('/country_summary', 'report\CountrySummaryController@country_summary');
$router->get('/program_summary', 'report\ProgramSummaryController@program_summary');
$router->get('/program_summary_last', 'report\ProgramSummaryController@program_summary_last');
$router->get('/program_summary/{id:[0-9]+}', 'report\ProgramSummaryController@find');

$router->get('/news', 'news\NewsController@News');
$router->get('/news/{id:[0-9]+}', 'news\NewsController@find');
$router->delete('/news/delete/{id:[0-9]+}', 'news\NewsController@delete');
// $router->post('/news/create', 'news\NewsController@create');

$router->get('/country/genders_by_country_id', 'report\CountrySummaryController@genders_by_country_id');
$router->get('/country/count_genders', 'report\CountrySummaryController@count_genders');
$router->get('/country/country_summary_by_country_id', 'report\CountrySummaryController@country_summary_by_country_id');

$router->get('/careerorganizationtype', 'careerorganizationtype\CareerOrganizationTypeController@careerorganizationtype');

// Authentication
$router->post('/authen', 'authen\AuthenController@authen');