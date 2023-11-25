<?php

$routes->group('credential', ['namespace' => '\Modules\Credential\Controllers'], function ($routes) {

  $routes->post('get_valid_user', 'Credential::get_valid_user');
});
