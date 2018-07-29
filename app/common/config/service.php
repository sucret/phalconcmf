<?php


/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {

	$dbConfig = $config->database->toArray();
	$adapter  = $dbConfig['adapter'];
	unset($dbConfig['adapter']);

	$class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;

	$conn = new $class($dbConfig);
	if (DEBUG) {
		$eventsManager = new Phalcon\Events\Manager();
		$eventsManager->attach('db', function ($event, $connection) {
			echo getTime() . " " . $connection->getSQLStatement() . '<br />';
		});
		$conn->setEventsManager($eventsManager);
	}
	return $conn;
});

$di->set('readdb', function () use ($config) {
	$dbConfig = $config->readdb->toArray();
	$adapter  = $dbConfig['adapter'];
	unset($dbConfig['adapter']);

	$class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;

	$conn = new $class($dbConfig);
	if (DEBUG) {
		$eventsManager = new Phalcon\Events\Manager();
		$eventsManager->attach('readdb', function ($event, $connection) {
			echo getTime() . " " . $connection->getSQLStatement() . '<br />';
			// error_log(getTime() . " read " . $connection->getSQLStatement() . PHP_EOL, 3, APP_PATH.'/cache/readdb.log' );

		});
		$conn->setEventsManager($eventsManager);
	}
	return $conn;
});

$di->set('mq', function(){

});