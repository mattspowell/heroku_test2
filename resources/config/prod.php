<?php
$app['log.level'] = Monolog\Logger::INFO;
$app['api.version'] = "v1";
$app['api.endpoint'] = "/api";
$app['kapow'] = array(
	'endpoint' => 'http://www.kapow.co.uk/scripts/sendsms.php',
	'username' => 'Unipro',
	'password' =>'Kapow1252',
	'returnid' => true
);
/**
 * MySQL
 */
//$app['db.options'] = array(
//  "driver" => "pdo_mysql",
//  "user" => "root",
//  "password" => "root",
//  "dbname" => "prod_db",
//  "host" => "prod_host",
//);
