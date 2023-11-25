<?php

$routes->group('utility', ['namespace' => '\Modules\Utility\Controllers'], function ($routes) {

  $routes->get('is_active_session', 'Utility::is_active_session');
});
