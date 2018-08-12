<?php

define('DEBUG', true);

define('LOGGER_ID', sha1(uniqid('', true)));

$config = [
	'database' => [
		'adapter'  => 'Mysql',
		'host'     => '172.17.0.5',
		'username' => 'root',
		'password' => 'root',
		'dbname'   => 'phalconcmf',
		'charset'  => 'utf8mb4',
		'port'     => 3306,
	],
	'readdb'   => [
		'adapter'  => 'Mysql',
		'host'     => '172.17.0.5',
		'username' => 'root',
		'password' => 'root',
		'dbname'   => 'phalconcmf',
		'charset'  => 'utf8mb4',
		'port'     => 3306,
	],

	'redis' => [
		'host'     => '172.17.0.4',
		'port'     => 6379,
		'password' => 'sucret',
		'dbindex'  => 0
	]
];

return new \Phalcon\Config($config);