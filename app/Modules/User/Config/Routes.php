<?php

$routes->group('user', ['namespace' => '\Modules\User\Controllers'], function ($routes) {

  $routes->get('/', 'User::index');
  $routes->get('search', 'User::search');
  $routes->get('add', 'User::add');
  $routes->get('edit/(:num)', 'User::edit/$1');
  $routes->post('submit', 'User::submit');
  $routes->get('delete/(:num)', 'User::delete/$1');
  $routes->get('reset_password/(:segment)', 'User::reset_password/$1');
});
