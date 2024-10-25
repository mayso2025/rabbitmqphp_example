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
		echo '<pre>' . print_r($response, true) . '</pre';
		//TODO edit necessary files and this to redirect back to login with user-friendly error message instead of staying on testRabbitMQClient.php
	}
}
else {

$response['message'] = "ERROR: no resposne from RabbitMQ";
echo '<pre>' ; print_r($response); echo '</pre';

}


//end of file
