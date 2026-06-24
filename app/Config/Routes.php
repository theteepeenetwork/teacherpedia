<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * Auto-routing (legacy) is enabled in Config\Routing and Config\Feature so that
 * the controller/method URL conventions used throughout this app keep working,
 * including POST form submissions that are not explicitly listed below.
 */

$routes->get('/', 'Home::index');

// Home controller
$routes->get('/vision', 'Home::index/vision');
$routes->get('/privacy-policy', 'Home::legal/privacy-policy');
$routes->get('/contact', 'Home::contact/contact');

// User/Users controller
$routes->get('/register', 'User\Users::register');
$routes->get('/login', 'User\Users::index/login');
$routes->get('/verify_email/(:any)', 'User\Users::verify_email');
$routes->get('/account/communication', 'User\Users::index/communication', ['filter' => 'auth']);
$routes->get('/account/subscription', 'User\Users::index/subscription', ['filter' => 'auth']);
$routes->get('/account/change_password', 'User\Users::index/change_password', ['filter' => 'auth']);
$routes->get('/account/logout', 'User\Users::logout', ['filter' => 'auth']);
$routes->get('/account/(:any)', 'User\Users::index/account', ['filter' => 'auth']);
$routes->get('/account', 'User\Users::index/account', ['filter' => 'auth']);
$routes->get('/user/(:any)', 'User\Users::index/account', ['filter' => 'auth']);
$routes->get('/user', 'User\Users::index/account', ['filter' => 'auth']);

// Admin controllers
$routes->get('/dashboard', 'Admin\Admin::index', ['filter' => 'auth']);
$routes->get('/admin/admin_users/(:any)', 'Admin\Admin_users::$1', ['filter' => 'auth']);
$routes->get('/admin/manage_gallery/(:any)', 'Admin\Manage_gallery::$1', ['filter' => 'auth']);
$routes->get('/admin/resources/(:any)', 'Admin\Resources::$1', ['filter' => 'auth']);
$routes->get('/admin_login', 'Admin\Admin_users::index/login');
$routes->get('/admin_register', 'Admin\Admin::login/register');
$routes->get('/admin/(:any)', 'Admin\Admin::index', ['filter' => 'auth']);
$routes->get('/admin', 'Admin\Admin::index', ['filter' => 'auth']);

// Resources controller (public)
// loadSheet receives the generator form submission (POST) and also works via GET.
// Accept both the singular and plural prefixes used by the resource form templates.
$routes->match(['get', 'post'], 'resource/loadSheet/(:any)', 'Resources::loadSheet/$1');
$routes->match(['get', 'post'], 'resources/loadSheet/(:any)', 'Resources::loadSheet/$1');
$routes->get('/resource/(:any)', 'Resources::load');

// Subjects / keystages
$routes->get('keystage/keystage2-ks2/numeracy', 'Resources::subjects/results/numeracy');
$routes->get('keystage/keystage2-ks2', 'Resources::view/subjects');
$routes->get('keystage/(:any)', 'Resources::view/keystages/$1');
$routes->get('keystage', 'Resources::view/keystages/$1');
