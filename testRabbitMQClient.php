#!/usr/bin/php
<?php

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

$request = array();
$request['type'] = "login";
$request['username'] = $_POST["username"];
$request['password'] = $_POST["password"];


$response = $client->send_request($request);
if ($response) { //as-is, it sends both success and failures
	if ($response['returnCode']){ //this specifies if logn is success (returnCode=1)
		header('Location: welcome.php');
		exit();
	}
	else {
		$error = urelncode("Failed to log in: " . ($response['message'] ?? "Incorrect Username or Password"));
		header("Location: /rabbitmq_example/index.html?error=$error");
		exit();
	}
}
else {

	$error = urelncode("ERROR: No Response from Server!");
	header("Location: /rabbitmq_example/index.html?error=$error");
	exit();

}


//end of file
