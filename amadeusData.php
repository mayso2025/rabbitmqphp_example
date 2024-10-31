<?php

require 'vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

	

$apiKey = $_ENV['API_KEY'];
$apiSecret = $_ENV['API_SECRET'];
$baseUrl = 'http://test.api.amadeus.com/v2';

#echo $apiKey . " " . $apiSecret;

function getAccessToken($apiKey, $apiSecret){
	$url = 'https://test.api.amadeus.com/v1/security/oauth2/token';

	$header= [
		'grant_type' => 'client_credentials',
		'client_id' => $apiKey,
		'client_secret' => $apiSecret
	];

	$ch = curl_init($url);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($header));

	$response = curl_exec($ch);
	curl_close($ch);

	$responseData = json_decode($response, true);
	print_r($responseData);
	return $responseData['access_token'] ?? null;
}
###

function getFlightOffers($accessToken){
	global $baseUrl;
	#$url = $baseUrl . "/shopping/flight-offers";
	$url = $baseUrl . "/shopping/flight-offers?originLocationCode=NYC&destinationLocationCode=LAX&departureDate=24-11-01&adults=1";

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Authorization: Bearer $accessToken"
	]);

	$response = curl_exec($ch);
	curl_close($ch);

	return json_decode($response,true);
}

###

$accessToken = getAccessToken($apiKey, $apiSecret);
if (isset($accessToken['access_token'])){
	$results = getFlightOffers($accessToken);
	print_r($results);
} else {
	echo "FAILED: NO ACCESS TOKEN\n";
}

?>
