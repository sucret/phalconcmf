<?php

namespace App\Common\Module;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use \Phalcon\Mvc\Dispatcher as PhDispatcher;
use Phalcon\Session\Adapter\Files as Session;


class Admin implements ModuleDefinitionInterface
{
	public function registerAutoloaders(DiInterface $di = null)
	{
		$loader = new Loader();


		$loader->registerNamespaces([
			                            'App\Admin\Controller' => ROOT_PATH . '/app/admin/controller',
			                            'App\Common\Model'     => ROOT_PATH . '/app/common/model',
			                            'App\Common\Library'   => ROOT_PATH . '/app/common/library'
		                            ]);
		$loader->register();

	}

	public function registerServices(DiInterface $di = null)
	{

		$di->set('view',
			function () {
				$view = new \Phalcon\Mvc\View();


				$view->setViewsDir(ROOT_PATH . '/app/admin/view');

				return $view;
			});

		$di->set('dispatcher',
			function () {
				$dispatcher = new Dispatcher();


				$dispatcher->setDefaultNamespace("App\Admin\Controller");

				return $dispatcher;
			});

		$di->setShared('session',
			function () {
				$session = new Session();
				$session->start();

				return $session;
			});

	}
}