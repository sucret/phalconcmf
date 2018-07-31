<?php



error_reporting(E_ALL);

// Default Timezone
date_default_timezone_set('Asia/Shanghai');

define('ROOT_PATH', dirname(dirname(__FILE__)));

include ROOT_PATH . '/vendor/autoload.php';

try
{

	/**
	 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
	 */
	$di = new \Phalcon\DI\FactoryDefault();

	$config = include ROOT_PATH . "/app/common/config/config.php";

	/**
	 * Read services
	 */
	include ROOT_PATH . "/app/common/config/service.php";

	/**
	 * Read router
	 */
	include ROOT_PATH . "/app/common/config/router.php";

	/**
	 * Handle the request
	 */
	$application = new \Phalcon\Mvc\Application();

	$application->setDI($di);

	/**
	 * Register application modules
	 */
	$application->registerModules([
		                              'admin' => [
			                              'className' => 'App\Common\Module\Admin',
			                              'path'      => ROOT_PATH . '/app/common/module/Admin.php'
		                              ]
	                              ]);

	echo $application->handle()
	                 ->getContent();

} catch (\Exception $ex)
{
	$message = '{code:' . $ex->getCode() . '->' . $ex->getMessage() . '} @' . str_replace(ROOT_PATH . '/',
	                                                                                      '',
	                                                                                      $ex->getFile()) . ':' . $ex->getLine();
	$di->get('logger')
	   ->log($message);


	echo $ex->getMessage();
}