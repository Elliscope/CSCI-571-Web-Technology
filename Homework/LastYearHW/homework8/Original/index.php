<?php

$address = "";
$city = "";
$state = "";
$degree = "us";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$address = $_GET["address"];
	$city = $_GET["city"];
	$state = $_GET["state"];
	$degree = $_GET["degree"];
	if ($address != "" && $city != "" && $state != "") {
		// echo "correct";
		getGeocode($address, $city, $state, $degree);
	}
}

function getGeocode($address, $city, $state, $degree) {
	$url = "https://maps.googleapis.com/maps/api/geocode/xml?sensor=false&key=AIzaSyBLwql6LQCI3FUkqB1so3wHOCqR2WrJDdE&address=".rawurlencode($address).",".rawurlencode($city).",".rawurlencode($state);
	// echo $url;
    $res = file_get_contents($url);
	$xml = simplexml_load_string($res);
	$lat = $xml->result->geometry->location->lat;
	$lng = $xml->result->geometry->location->lng;
	if ($lat != "" && $lng != "") {
		getForecast($lat, $lng, $degree);
	} else {
		echo "{}";
	}
}

function getForecast($lat, $lng, $degree) {
	$url = "https://api.forecast.io/forecast/3c16f6127da68eea92722d986da61eb6/".$lat.",".$lng."?units=".$degree."&exclude=flags";
	// echo $url;
	$res = file_get_contents($url);
	echo $res;
}

?>