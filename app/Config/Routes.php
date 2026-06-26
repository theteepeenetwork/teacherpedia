<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * Teacherpedia (rebuild) — explicit routes. Auto-routing is disabled
 * (see Config\Routing::$autoRoute = false). Add every endpoint here.
 *
 * IA:
 *   Public  : /  /browse  /pricing  /vision  /contact  /privacy
 *   Auth UI : /login  /register(POST)  /logout  /admin/login
 *   Tools   : /build  /code-breaker            (public; Save requires login)
 *   Account : /account  (filter: auth)
 *   Admin   : /admin  /admin/studio  (filter: admin)
 */

// ---- Public marketing ----
$routes->get('/', 'Home::index');
$routes->get('browse', 'Browse::index');
$routes->get('pricing', 'Pages::pricing');
$routes->get('vision', 'Pages::vision');
$routes->get('privacy', 'Pages::privacy');
$routes->get('contact', 'Contact::index');
$routes->post('contact', 'Contact::submit');

// ---- Auth ----
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->post('register', 'Auth::attemptRegister');
$routes->get('logout', 'Auth::logout');
$routes->get('verify-email/(:any)/(:any)', 'Auth::verifyEmail/$1/$2');
$routes->get('admin/login', 'Auth::adminLogin');           // public — must precede the admin group
$routes->post('admin/login', 'Auth::attemptAdminLogin');

// ---- Activity tools (public; saving requires auth) ----
$routes->get('build', 'Build::index');
$routes->get('build/(:num)', 'Build::index/$1');            // open a saved sheet
$routes->get('code-breaker', 'CodeBreaker::index');
$routes->get('maths-maze', 'MathsMaze::index');
$routes->get('columns', 'Columns::index');                  // Column Methods (?op=add|subtract|multiply|divide)
$routes->get('treasure-hunt', 'TreasureHunt::index');
$routes->get('loop-cards', 'LoopCards::index');
$routes->get('bingo', 'Bingo::index');

// ---- Account (logged-in teachers) ----
$routes->group('account', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('', 'Account::index');
    $routes->post('save', 'Account::save');                 // Save sheet from a tool (AJAX)
    $routes->get('delete/(:num)', 'Account::delete/$1');
});

// ---- Admin (admins only) ----
$routes->group('admin', ['filter' => 'admin'], static function (RouteCollection $routes): void {
    $routes->get('', 'Admin\Admin::index');
    $routes->get('studio', 'Admin\Studio::index');
    $routes->get('studio/(:num)', 'Admin\Studio::index/$1'); // review a submission
    $routes->post('studio/draft', 'Admin\Studio::draft');
    $routes->post('studio/submit', 'Admin\Studio::submit');
    $routes->get('submissions/approve/(:num)', 'Admin\Admin::approve/$1');
});
