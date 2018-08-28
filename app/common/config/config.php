<?php

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

	'logger' => [
		'line' => [
			'format'     => '[%date%][%type%] %message%',
			'dateFormat' => 'Y-m-d H:i:s'
		],
		'file' => [
			'alert'     => ROOT_PATH . '/log/alert/' . date('Ymd') . '.log',
			'critical'  => ROOT_PATH . '/log/critical/' . date('Ymd') . '.log',
			'debug'     => ROOT_PATH . '/log/debug/' . date('Ymd') . '.log',
			'error'     => ROOT_PATH . '/log/error/' . date('Ymd') . '.log',
			'emergency' => ROOT_PATH . '/log/emergency/' . date('Ymd') . '.log',
			'info'      => ROOT_PATH . '/log/info/' . date('Ymd') . '.log',
			'notice'    => ROOT_PATH . '/log/notice/' . date('Ymd') . '.log',
			'warning'   => ROOT_PATH . '/log/warning/' . date('Ymd') . '.log'
		]
	]
];

return new \Phalcon\Config($config);