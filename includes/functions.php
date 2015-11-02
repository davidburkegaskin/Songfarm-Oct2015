<?php

/**
* Function - redirect to
* a new location
*
* @param string location
*/
function redirect_to($location) {
	header("Location: " . $location);
	exit;
}

/**
*	Function - Attempt to validate
* user IP address.
*
* Use IP address to collect user's
* Country Code and Country Name
*
* @return array user's country code, country name
*
*/
function generate_ip_data(){
	// if the Server detects an IP address, set it to $user_ip variable
	if(isset($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)){
		$user_ip = $_SERVER['REMOTE_ADDR']; // equates to ::1 no location information
		$user_ip = ""; // equates to nothing, outputs NL Netherlands
		/* test ip in Ecuador */
		// $user_ip = '181.196.204.134';
		/* IP for Toronto */
		$user_ip = '192.206.151.131';
	} else {
		$user_ip = "";
	}

	// get contents of the IP address
	$ip_contents = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$user_ip));

	$ip_data = [];
	// loop through contents and extract certain values
	foreach ($ip_contents as $key => $value) {
		$key = substr($key, 10); // remove 'geoplugin' part of key..
		if( // if there are matches for any of these conditions, place into $ip_data[]
		$key == 'countryCode' || 	$key == 'countryName'	|| $key == 'city' || $key == 'continentCode')	{
			$ip_data[$key] = $value;
		}
	} // end of foreach loop

	$country_array = [];
	// create variables to contain ip keys
	$country_array[] = strtoupper($ip_data['countryCode']); // make sure country code is always uppercase
	$country_array[] = ucfirst($ip_data['countryName']); // first letter is always upper case
	$country_array[] = ucfirst($ip_data['city']); // first letter is always upper case
	$country_array[] = strtoupper($ip_data['continentCode']); // make sure continent code is always uppercase

	return $country_array;
}



?>
