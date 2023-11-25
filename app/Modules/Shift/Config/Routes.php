<?php

$routes->group('shift', ['namespace' => '\Modules\Shift\Controllers'], function ($routes) {

  $routes->get('/', 'Shift::index');
  $routes->get('search', 'Shift::search');
  $routes->get('add', 'Shift::add');
  $routes->get('edit/(:num)', 'Shift::edit/$1');
  $routes->post('submit', 'Shift::submit');
  $routes->get('delete/(:num)', 'Shift::delete/$1');
});
