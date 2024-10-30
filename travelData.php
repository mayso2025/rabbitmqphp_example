<?php

# referenced official documentation + previous php calls done for it202

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmtable(__DIR__);
$dotenv->load();

$clientId = $_ENV['API_KEY'];
$clientSecret = $_ENV['API_SECRET'];

function getAccessToken($clientId, $clientSecret) {
	$url = 'https://test.api.amadeus.com/v1/security/oauth2/token';
	$data = [
		'grant_type' => 'client_credentials',
		'client_id' => $clientId,
		'client_secret'=$clientSecret,
	];
}


?>
