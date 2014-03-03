<?php

use Carbon\Carbon;

function visit($url)
{
	$agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSLVERSION, 3);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$page = curl_exec($ch);
	//echo curl_error($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if ($httpcode >= 200 && $httpcode < 400) return true;
	else return false;
}

function gravatar_url($email = "victor.tolbert@gmail.com")
{
	if (visit('yahoo.com')) return "http://www.gravatar.com/avatar/" . md5($email) . '?s=48';
	return "/img/down.gif";
}

function gravatar_profile($email = "victor.tolbert@gmail.com")
{
	if (visit('yahoo.com')) return "http://www.gravatar.com/" . md5($email);
	return "/img/down.gif";
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array())
{
	$url = 'http://www.gravatar.com/avatar/';
	$url .= md5(strtolower(trim($email)));
	$url .= "?s=$s&d=$d&r=$r";
	if ($img) {
		$url = '<img src="' . $url . '"';
		foreach ($atts as $key => $val)
			$url .= ' ' . $key . '="' . $val . '"';
		$url .= ' />';
	}
	return $url;
}

function convert_file_size($size)
{
	if (($size / 1073741824) > 1) {
		$show_size = round(($size / 1073741824), 2) . "Gb";
	} else if (($size / 1048576) > 1) {
		$show_size = round(($size / 1048576), 2) . "Mb";
	} else if (($size / 1024) > 1) {
		$show_size = round(($size / 1024), 2) . "Kb";
	} else {
		$show_size = $size . " bytes";
	}
	return $show_size;
}

function format_seconds($seconds)
{
	return date("i:s", $seconds);
}

function format_timestamp($timestamp)
{
	if (Carbon::now()->subDays(3) < $timestamp) {
		return $timestamp->diffForHumans();
	}
	return date('D, M j, Y,  g:i a', strtotime($timestamp));
}

function format_phone($phoneNumber)
{
	$phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

	if (strlen($phoneNumber) > 10) {
		$countryCode = substr($phoneNumber, 0, strlen($phoneNumber) - 10);
		$areaCode = substr($phoneNumber, -10, 3);
		$nextThree = substr($phoneNumber, -7, 3);
		$lastFour = substr($phoneNumber, -4, 4);

		$phoneNumber = '+' . $countryCode . ' (' . $areaCode . ') ' . $nextThree . '-' . $lastFour;
	} else if (strlen($phoneNumber) == 10) {
		$areaCode = substr($phoneNumber, 0, 3);
		$nextThree = substr($phoneNumber, 3, 3);
		$lastFour = substr($phoneNumber, 6, 4);

		$phoneNumber = '(' . $areaCode . ') ' . $nextThree . '-' . $lastFour;
	} else if (strlen($phoneNumber) == 7) {
		$nextThree = substr($phoneNumber, 0, 3);
		$lastFour = substr($phoneNumber, 3, 4);

		$phoneNumber = $nextThree . '-' . $lastFour;
	}

	return $phoneNumber;
}

