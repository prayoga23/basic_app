<?php

$routes->group('login', ['namespace' => '\Modules\Login\Controllers'], function ($routes) {

	$routes->get('/', 'Login::index');
	$routes->post('validate_login', 'Login::validate_login');
});
