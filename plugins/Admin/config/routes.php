<?php
use Cake\Routing\Router;
Router::plugin('Admin', function ($routes) {
    Router::connect('/admin', ['plugin' => 'Admin','controller' => 'Users', 'action' => 'index']);
    $routes->fallbacks('InflectedRoute');
});
//Router::plugin('Admin', function ($routes) {
//    Router::scope('/', ['plugin' => 'Admin'], function ($routes) {
//    Router::connect('/admin', ['plugin' => 'Admin','controller' => 'Users', 'action' => 'login']);
//
//    $routes->prefix('admin', function ($routes) {
//        $routes->fallbacks();
//    });
//    $routes->fallbacks();
//    });
//});