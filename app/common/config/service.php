<?php

use Phalcon\Logger\Formatter\Line as LineFormatter;


$di->set('profiler',
	function () {

		return new  \Phalcon\Db\Profiler();

	},
	     true);

$di->setShared('redis',
	function () use ($config) {
		$redis = new \Redis();
		$redis->connect($config->redis->host, $config->redis->port);
		$redis->auth($config->redis->password);

		return $redis;
	});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db',
	function () use ($config, $di) {

		$eventsManager = new \Phalcon\Events\Manager();
		$dbConfig      = $config->database->toArray();
		$adapter       = $dbConfig['adapter'];
		unset($dbConfig['adapter']);

		$class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;

		/**
		 *
		 */
		$conn = new $class($dbConfig);

		if (DEBUG)
		{
			$profiler = $di->get('profiler');

			$eventsManager->attach('db',
				function ($event, $conn) use ($profiler, $di) {

					if ($event->getType() == 'beforeQuery')
					{
						//在sql发送到数据库前启动分析
						$profiler->startProfile($conn->getSQLStatement());
					}
					if ($event->getType() == 'afterQuery')
					{
						//在sql执行完毕后停止分析
						$profiler->stopProfile();
						//获取分析结果
						$profile = $profiler->getLastProfile();

						//日志记录
						$di->get('logger')
						   ->info([
							          'sql'   => $profile->getSQLStatement(),
							          'start' => $profile->getInitialTime(),
							          'end'   => $profile->getFinalTime(),
							          'spend' => $profile->getTotalElapsedSeconds()
						          ]);
					}
				});
		}

		$conn->setEventsManager($eventsManager);


		return $conn;
	});

/**
 * DI注册modelsManager服务
 */
$di->setShared('modelsManager',
	function () use ($di) {
		return new Phalcon\Mvc\Model\Manager();
	});


/**
 * 注册日志
 */
$di->set('logger',
	function () {
		$logger    = new App\Common\Library\Logger(ROOT_PATH . '/log/' . date('Ymd') . '.log');
		$formatter = new LineFormatter("[%date%]-[%type%]-[" . LOGGER_ID . "] %message%", 'Y-m-d H:i:s');
		$logger->setFormatter($formatter);

		return $logger;
	});

$di->set('mq',
	function () {

	});