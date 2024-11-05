<?php

require 'vendor/autoload.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

	

$apiKey = $_ENV['API_KEY'];
$apiSecret = $_ENV['API_SECRET'];
$baseUrl = 'https://test.api.amadeus.com';



#==========
#getAccessToken
#==========
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

#==========
#flightList
#==========
function flightList($accessToken){
//TODO need to accept an array of options
	$locationCode = 'NYC';
	$callUrl = (string)"/v1/shopping/flight-destinations?origin=" . $locationCode;
	
	return processCall($accessToken, $callUrl);
}


#==========
#hotelList
#==========
function hotelList($accessToken){
	//TODO need to accept an array of options
	//
	$locationCode = 'NYC';
	$callUrl = (string)"/v1/reference-data/locations/hotels/by-city?cityCode=" . $locationCode;

	return processCall($accessToken, $callUrl);
}

#==========
#processCall
#==========
function processCall($accessToken, $callUrl){
	global $baseUrl;

	//Working below !!
	//
	//$url = $baseUrl . "/v1/reference-data/locations/hotels/by-city?cityCode=PAR";
	
	//this also works but needs specifications
	//$url = $baseUrl . "/v2/shopping/flight-offers";

	//this works best
	//$url = $baseUrl . "/shopping/flight-destinations?origin=PAR";


	$url = $baseUrl . $callUrl;

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



#==========
$accessToken = getAccessToken($apiKey, $apiSecret);
if ($accessToken){
	/*
	switch ($request['type']){
	//case
	}
	 */
	$results = flightList($accessToken);

	//$results = hotelList($accessToken);
	//TODO make switch-case and don't hard-code function calls ^

	print_r($results);
} else {
	echo "FAILED: NO ACCESS TOKEN\n";
}

?>
