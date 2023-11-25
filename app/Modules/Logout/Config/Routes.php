<?php

$routes->group('logout', ['namespace' => '\Modules\Logout\Controllers'], function ($routes) {

  $routes->add('/', 'Logout::index');
});
