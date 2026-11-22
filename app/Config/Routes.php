<?php

use CodeIgniter\Router\RouteCollection;
$routes->get('/', 'ProdutosController::index');

/** @var RouteCollection $routes */
$routes->group('produtos', function ($routes) {
    $routes->get('/', 'ProdutosController::index');
    $routes->post('criar', 'ProdutosController::criar');
    $routes->post('vender/(:num)', 'ProdutosController::vender/$1');
    $routes->post('repor/(:num)', 'ProdutosController::repor/$1');
    $routes->post('deletar/(:num)', 'ProdutosController::deletar/$1');
});