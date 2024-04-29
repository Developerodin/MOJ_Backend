<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//user 
$routes->get('home', 'Home::index');
$routes->get('/', 'Home::login');
$routes->post('login/authenticate', 'Home::authenticate');
$routes->post('/admin_register', 'Home::admin_register');
$routes->get('/logout', 'Home::logout');
$routes->get('/user-list', 'Home::user_list');
$routes->get('/hotel-list', 'Home::hotel_list');
$routes->get('/agent-list', 'Home::agent_list');
$routes->get('/job-list', 'Home::job_list');

$routes->post('/auth/number_check', 'Auth::check_mobile');
$routes->post('/users/work_exp', 'Users::work_ex');
$routes->post('/auth/verify_otp/(:num)', 'Auth::verifyOTP/$1');
$routes->post('/auth/register', 'Auth::register');
$routes->post('/auth/user_update','Auth::user_update');
$routes->post('/user_update','Auth::user_update_web');


// Job 
$routes->get('/job', 'Job::index');
$routes->post('/job/store', 'Job::store');
$routes->post('/job/Byid/(:num)','Job::show/$1');
$routes->post('/job/update/(:num)','Job::update/$1');
$routes->post('/job/delete/(:num)','Job::distroy/$1');  // user deleted


// Job apply
$routes->get('/job_apply', 'Job_Apply::index');
$routes->post('/job_apply/store', 'Job_Apply::store');
$routes->post('/job_apply/Byid/(:num)','Job_Apply::show/$1');
$routes->post('/job_apply/update/(:num)','Job_Apply::update/$1');
$routes->post('/job_apply/delete/(:num)','Job_Apply::distroy/$1');  // user deleted


// Job save 
$routes->get('/job_saved', 'Job_save::index');
$routes->post('/job_save/store', 'Job_save::store');
$routes->post('/job_saved/Byuserid/(:num)','Job_save::show/$1');
$routes->post('/job_save/delete/(:num)','Job_save::distroy/$1');  // user deleted

// user ex.
$routes->get('/user/workingExperience', 'Users::get');
$routes->post('/user_work_ex/store', 'Users::work_ex');
$routes->get('/user_work_ex/By_userId/(:num)','Users::work_show/$1');
$routes->post('/user_work_ex/Update_ByuserId/(:num)','Users::work_ex_update/$1');
$routes->post('/user_work_ex/delete/(:num)','Users::delete_w_ex/$1');  // user deleted
// user education.
$routes->get('/user/education', 'Users::edu_get');
$routes->post('/user_education/store', 'Users::education');
$routes->get('/user_education/By_userId/(:num)','Users::education_show/$1');
$routes->post('/user_education/Update_ByuserId/(:num)','Users::education_update/$1');
$routes->post('/user_education/delete/(:num)','Users::delete_education/$1');  // user deleted



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
