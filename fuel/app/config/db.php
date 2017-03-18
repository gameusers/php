<?php
/**
 * The development database settings. These get merged with the global settings.
 */

return array(
	'default' => array(
		'connection'  => array(
			'dsn'        => 'mysql:dbname=gameusers; host=127.0.0.1',
			'username'   => 'データベースのユーザー名',
			'password'   => 'データベースのパスワード',
		),
		'charset' => 'utf8',
		'profiling' => true,
	),
);
