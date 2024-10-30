<?php

# referenced official documentation + previous php calls done for it202

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmtable(__DIR__);
$dotenv->load();

$clientId = $_ENV['API_KEY'];
$clientSecret = $_ENV['API_SECRET'];

function getAccessToken($clientId, $clientSecret) {
	$url = 'https://test.api.amadeus.com/v1/security/oauth2/token';
	$headers = [
		'grant_type' => 'client_credentials',
		'client_id' => $clientId,
		'client_secret'=$clientSecret,
	];

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS http_build_query($headers));

	$response = curl_exec($ch);
	curl_close($ch);

	$responseData= json_decode($response,true);
	return $responseData['access_token'];

}


?>
