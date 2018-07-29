<?php

$config = [
	'database'    => [
		'adapter'  => 'Mysql',
		'host'     => '172.17.0.5',
		'username' => 'root',
		'password' => 'root',
		'dbname'   => 'phalconcmf',
		'charset'  => 'utf8mb4',
		'port'     => 3306,
	],
	'readdb'      => [
		'adapter'  => 'Mysql',
		'host'     => '172.17.0.5',
		'username' => 'root',
		'password' => 'root',
		'dbname'   => 'phalconcmf',
		'charset'  => 'utf8mb4',
		'port'     => 3306,
	]
];

return new \Phalcon\Config($config);