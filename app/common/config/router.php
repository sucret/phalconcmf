<?php

use Phalcon\Mvc\Router;

$di->set('router',
	function () {
		$router = new Router(false);

		$router->setDefaultModule('admin');

		$router->add('/admin',
		             [
			             'module'     => 'admin',
			             'controller' => 'index',
			             'action'     => 'index'
		             ]);

		$router->add('/admin/:controller/:action/:params',
		             [
			             'module'     => 'admin',
			             'controller' => 1,
			             'action'     => 2,
			             'params'     => 3,
		             ]);


		$router->add('/api',
		             [
			             'module'     => 'api',
			             'controller' => 'index',
			             'action'     => 'index'
		             ]);

		$router->add('/api/:controller/:action/:params',
		             [
			             'module'     => 'api',
			             'controller' => 1,
			             'action'     => 2,
			             'params'     => 3,
		             ]);


		//		$router->add('/:controller/:action/:params',
		//		             [
		//			             'module'     => 'api',
		//			             'controller' => 1,
		//			             'action'     => 2,
		//			             'params'     => 3
		//		             ]);


		$router->notFound([
			                  'controller' => 'index',
			                  'action'     => 'notFound'
		                  ]);


		return $router;
	});



