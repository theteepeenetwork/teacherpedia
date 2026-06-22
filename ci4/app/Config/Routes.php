<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/', 'Home::index');

//$routes->get('/', 'Home::notice');
//$routes->get('/(:any)', 'Home::notice');


//$routes->get('/login', 'User/Users::index/login');

//Home  controller
$routes->get('/vision', 'Home::index/vision');
$routes->get('/privacy-policy', 'Home::legal/privacy-policy');
$routes->get('/contact', 'Home::contact/contact');

//User/User controller
$routes->get('/register', 'User/Users::register');
$routes->get('/login', 'User/Users::index/login');
$routes->get('/verify_email/(:any)', 'User/Users::verify_email');
$routes->get('/account/communication', 'User/Users::index/communication', ['filter' => 'auth']);
$routes->get('/account/subscription', 'User/Users::index/subscription', ['filter' => 'auth']);
$routes->get('/account/change_password', 'User/Users::index/change_password', ['filter' => 'auth']);
$routes->get('/account/logout', 'User/Users::logout', ['filter' => 'auth']);
$routes->get('/account/(:any)', 'User/Users::index/account', ['filter' => 'auth']);
$routes->get('/account', 'User/Users::index/account', ['filter' => 'auth']);
$routes->get('/user/(:any)', 'User/Users::index/account', ['filter' => 'auth']);
$routes->get('/user', 'User/Users::index/account', ['filter' => 'auth']);


//Admin/Admin controller
$routes->get('/dashboard', 'Admin\Admin::index', ['filter' => 'auth']);
$routes->get('/admin/admin_users/(:any)', 'Admin\Admin_users::$1', ['filter' => 'auth']);
$routes->get('/admin/manage_gallery/(:any)', 'Admin\Manage_gallery::$1', ['filter' => 'auth']);
$routes->get('/admin/resources/(:any)', 'Admin\Resources::$1', ['filter' => 'auth']);
$routes->get('/admin_login', 'Admin\Admin_users::index/login');
$routes->get('/admin_register', 'Admin\Admin::login/register');

$routes->get('/admin/(:any)', 'Admin/Admin::index', ['filter' => 'auth']);
$routes->get('/admin', 'Admin/Admin::index', ['filter' => 'auth']);


/*



//admin/admin controller
$routes->get('/admin', 'Admin\Admin::index', ['filter' => 'auth']);
//$routes->get('/admin/(:any)', 'Admin\Resources::index/$1', ['filter' => 'auth']);


//$routes->get('keystage', 'Resources::view/keystages');
*/


//resources controller
$routes->get('/resource/loadSheet/(:any)', 'Resources::loadSheet');
$routes->get('/resource/(:any)', 'Resources::load');



//subjects
$routes->get('keystage/keystage2-ks2/numeracy', 'Resources::subjects/results/numeracy');
$routes->get('keystage/keystage2-ks2', 'Resources::view/subjects');
$routes->get('keystage/(:any)', 'Resources::view/keystages/$1');
$routes->get('keystage', 'Resources::view/keystages/$1');




//images




/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}