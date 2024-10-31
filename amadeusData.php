<?php

require 'vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

	

$apiKey = $_ENV['API_KEY'];
$apiSecret = $_ENV['API_SECRET'];
$baseUrl = 'https://test.api.amadeus.com/v1';

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
	return $responseData['access_token'] ?? null;
}
###

function getFlightOffers($accessToken){
	global $baseUrl;
	#$url = "https://test.api.amadeus.com/v2/shopping/flight-offers";
	$url = $baseUrl . "/shopping/flight-destinations?origin=PAR";
	#$url = $baseUrl . "/shopping/flight-offers?originLocationCode=NYC&destinationLocationCode=LAX&departureDate=24-11-01&adults=1";

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Authorization: Bearer $accessToken",
		
		#"Content-Type:application/json"
	]);

	$response = curl_exec($ch);
	curl_close($ch);
	return json_decode($response,true);
}

###

$accessToken = getAccessToken($apiKey, $apiSecret);
if ($accessToken){
	$results = getFlightOffers($accessToken);
	print_r($results);
} else {
	echo "FAILED: NO ACCESS TOKEN\n";
}

?>
