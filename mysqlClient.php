#!/usr/bin/php
<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = getenv('DB_HOST');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASS');
$dbName = getenv('DB_NAME');

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);

if ($conn->connect_error){
	return array("returnCode" => '0', 'message' => "Database connection error");
}


try {
	//TODO

} finally {
	if ($stmt){
		$stmt->close();
	}
	$conn->close();
}

?>
