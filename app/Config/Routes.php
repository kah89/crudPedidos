<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('clientes', function($routes){
        $routes->get('/', 'Clientes::index');
        $routes->post('novo', 'Clientes::cadastroCliente');
        $routes->post('editar', 'Clientes::editarCliente');
        $routes->get('excluir/(:any)', 'Clientes::deletarCliente/$1');
    });
$routes->group('pedidos', function($routes){
        $routes->get('/', 'Pedidos::index');
        $routes->post('novo', 'Pedidos::cadastroPedido');
        $routes->post('editar/(:any)', 'Pedidos::editarPedido/$1');
        $routes->get('excluir/(:any)', 'Pedidos::deletarPedido/$1');
    });
    
$routes->group('produtos', function($routes){
        $routes->get('/', 'Produtos::index');
        $routes->post('novo', 'Produtos::cadastroProduto');
        $routes->post('editar/(:any)', 'Produtos::editarProduto/$1');
        $routes->get('excluir/(:any)', 'Produtos::deletarProduto/$1');
    });
