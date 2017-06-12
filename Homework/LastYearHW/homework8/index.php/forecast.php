<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Forecast Search</title>
	<style type="text/css">
	
	#output {
		width: 400px;
		margin: 0 auto;
		padding: 20px;
		margin-top: 50px; 
	}

	#search {
		width: 400px;
		border: 2px solid black;
		margin: 0 auto;
		padding: 20px;
	}
	
	h2 {
		margin: 2px;
	}
	</style>
</head>
<body>
	<h1 align="center">Forecast Search</h1>
	<div id="search" align="center">
		<form name="searchForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<table>
				<tr>
					<td>Street Address:*</td>
					<td><input name="address" type="text" value="<?php echo isset($_POST['address']) ? htmlentities($_POST['address']) : ''; ?>"></input></td>
					<td width="80px"></td>
				</tr>
				<tr>
					<td>City:*</td>
					<td><input name="city" type="text" value="<?php echo isset($_POST['city']) ? htmlentities($_POST['city']) : ''; ?>"></input></td>
				</tr>
				<tr>
					<td>State:*</td>
					<td>
						<select name="state">
						<?php

						$states = array(
							"" => 'Select your state',
							"AL" => 'Alabama',
							"AK" => 'Alaska',
							"AZ" => 'Arizona',
							"AR" => 'Arkansas',
							"CA" => 'California',
							"CO" => 'Colorado',
							"CT" => 'Connecticut',
							"DE" => 'Delaware',
							"DC" => 'District Of Columbia',
							"FL" => 'Florida',
							"GA" => 'Georgia',
							"HI" => 'Hawaii',
							"ID" => 'Idaho',
							"IL" => 'Illinois',
							"IN" => 'Indiana',
							"IA" => 'Iowa',
							"KS" => 'Kansas',
							"KY" => 'Kentucky',
							"LA" => 'Louisiana',
							"ME" => 'Maine',
							"MD" => 'Maryland',
							"MA" => 'Massachusetts',
							"MI" => 'Michigan',
							"MN" => 'Minnesota',
							"MS" => 'Mississippi',
							"MO" => 'Missouri',
							"MT" => 'Montana',
							"NE" => 'Nebraska',
							"NV" => 'Nevada',
							"NH" => 'New Hampshire',
							"NJ" => 'New Jersey',
							"NM" => 'New Mexico',
							"NY" => 'New York',
							"NC" => 'North Carolina',
							"ND" => 'North Dakota',
							"OH" => 'Ohio',
							"OK" => 'Oklahoma',
							"OR" => 'Oregon',
							"PA" => 'Pennsylvania',
							"RI" => 'Rhode Island',
							"SC" => 'South Carolina',
							"SD" => 'South Dakota',
							"TN" => 'Tennessee',
							"TX" => 'Texas',
							"UT" => 'Utah',
							"VT" => 'Vermont',
							"VA" => 'Virginia',
							"WA" => 'Washington',
							"WV" => 'West Virginia',
							"WI" => 'Wisconsin',
							"WY" => 'Wyoming'
						 );
						
						foreach ($states as $ss => $s) {
							$sel = (isset($_POST["state"])&&$_POST["state"]==$ss)?"selected":'';
							echo "<option $sel value='$ss'>$s</option>";
						}

						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Degree:*</td>
					<td>
						<input type="radio" name="degree" value="us" <?php echo isset($_POST["degree"])?(($_POST["degree"]=="us")?'checked':''):'checked'; ?>>Fahrenheit</input>
						<input type="radio" name="degree" value="si" <?php echo isset($_POST["degree"])?(($_POST["degree"]=="si")?'checked':''):''; ?>>Celsius</input>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="submit" name="Search" value="search" onclick="validate()"></input>
						<button type="button" name="clear" value="clear" onclick="clearForm()">Clear</button>
					</td>
				</tr>
			</table>
		</form>
		<p style="font-style:italic;" align="left">*- Mandatory fields.</p>
		<a href="http://forecast.io/">Powered by Forecast.io</a>
	</div>





<script type="text/javascript">

function clearForm() {
	document.searchForm.address.value = "";
	document.searchForm.city.value = "";
	document.searchForm.state.value = "";
	document.searchForm.degree.value = "us";
	document.getElementById("output").remove();
}

function validate() {
	var address = document.searchForm.address.value;
	var city = document.searchForm.city.value;
	var state = document.searchForm.state.value;

	var flag = 0;

	if (address == undefined || address == "") {
		flag += 1;
	}
	if (city == undefined || city == "") {
		flag += 2;
	}
	if (state == undefined || state == "") {
		flag += 4;
	}
	var alertArray = [];
	alertArray[1] = "Street Address";
	alertArray[2] = "City";
	alertArray[3] = "Street Address and City";
	alertArray[4] = "State";
	alertArray[5] = "Street Address and State";
	alertArray[6] = "City and State";
	alertArray[7] = "Street Address, City and State";
	if (flag != 0) {
		alert("Please enter value for "+alertArray[flag]);
	}
}

</script>




<?php
$address = "";
$city = "";
$state = "";
$degree = "us";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$address = $_POST["address"];
	$city = $_POST["city"];
	$state = $_POST["state"];
	$degree = $_POST["degree"];
	if ($address != "" && $city != "" && $state != "") {
		getGeocode($address, $city, $state, $degree);
	}
}

function getGeocode($address, $city, $state, $degree) {
	$url = "https://maps.googleapis.com/maps/api/geocode/xml?sensor=false&key=AIzaSyBLwql6LQCI3FUkqB1so3wHOCqR2WrJDdE&address=".rawurlencode($address).",".rawurlencode($city).",".rawurlencode($state);
    $res = file_get_contents($url);
	$xml = simplexml_load_string($res);
	$lat = $xml->result->geometry->location->lat;
	$lng = $xml->result->geometry->location->lng;
	if ($lat != "" && $lng != "") {
		getForecast($lat, $lng, $degree);
	}
}

function getForecast($lat, $lng, $degree) {
	$url = "https://api.forecast.io/forecast/3c16f6127da68eea92722d986da61eb6/".$lat.",".$lng."?units=".$degree."&exclude=flags";
	$res = file_get_contents($url);
	$res = json_decode($res, true);
	$timezone = $res["timezone"];
    $summary = $res["currently"]["summary"];
    $icon = $res["currently"]["icon"];
    $temperature = (float)$res["currently"]["temperature"];
    $precipIntensity = (float)$res["currently"]["precipIntensity"];
    $precipProbability = (float)$res["currently"]["precipProbability"]*100;
   	$windSpeed = (float)$res["currently"]["windSpeed"];
   	$dewPoint = (float)$res["currently"]["dewPoint"];
   	$humidity = (float)$res["currently"]["humidity"]*100;
   	$visibility = (float)$res["currently"]["visibility"];
   	$sunrise = $res["daily"]["data"][0]["sunriseTime"];
   	$sunset = $res["daily"]["data"][0]["sunsetTime"];

   	date_default_timezone_set($timezone);

   	$precipIntensityValue = "None";
   	if ($precipIntensity >= 0 && $precipIntensity < 0.002) {
   		$precipIntensityValue = "None";
   	} elseif ($precipIntensity >= 0.002 && $precipIntensity < 0.017) {
   		$precipIntensityValue = "Very Light";
   	} elseif ($precipIntensity >= 0.017 && $precipIntensity < 0.1) {
   		$precipIntensityValue = "Light";
   	} elseif ($precipIntensity >= 0.1 && $precipIntensity < 0.4) {
   		$precipIntensityValue = "Moderate";
   	} else {
   		$precipIntensityValue = "Heavy";
   	}

   	if ($degree == "si") {
   		$precipIntensity *= 0.0393701;
   	}

   	$iconList = array(
   		'clear-day' => "clear.png",
   		'clear-night' => "clear_night.png",
   		'rain' => "rain.png",
   		'snow' => "snow.png",
   		'sleet' => "sleet.png",
   		'wind' => "wind.png",
   		'fog' => "fog.png",
   		'cloudy' => "cloudy.png",
   		'partly-cloudy-day' => "cloud_day.png",
   		'partly-cloudy-night' => "cloud_night.png"
   		);

   	echo "<div id='output' align='center' style='border: 2px solid black;'><h2>".$summary."</h2>";
   	echo "<h2>".round($temperature)."&#176"." ".($degree=="us"?"F":"C")."</h2>";
    echo "<img src='http://localhost/img/".$iconList[$icon]."' alt='".$summary."' title='".$summary."'>";
   	//echo "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/".$iconList[$icon]."' alt='".$summary."' title='".$summary."'>";
   	echo "<table><tr><td width='220px'>Precipitation:</td><td width='100px'>".$precipIntensityValue."</td></tr>";
   	echo "<tr><td>Chance of Rain:</td><td>".$precipProbability."%</td></tr>";
   	echo "<tr><td>Wind Speed:</td><td>".round($windSpeed)." ".($degree=="us"?"mph":"m/s")."</td></tr>";
   	echo "<tr><td>Dew Point:</td><td>".round($dewPoint)."&#176"." ".($degree=="us"?"F":"C")."</td></tr>";
   	echo "<tr><td>Humidity:</td><td>".$humidity."%</td></tr>";
   	echo "<tr><td>Visibility:</td><td>".round($visibility)." ".($degree=="us"?"mi":"km")."</td></tr>";
   	echo "<tr><td>Sunrise:</td><td>".date("h:i A", $sunrise)."</td></tr>";
   	echo "<tr><td>Sunset:</td><td>".date("h:i A", $sunset)."</td></tr></table></div>";
}

?>

<noscript></body>
</html>