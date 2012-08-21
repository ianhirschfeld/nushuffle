<?php
/**
* Print data for debugging purposes
*
* @param Mixed $d data to be printed
* @return Mixed the data from $d
*/
function d($d){
	echo '<pre>';
	print_r($d);
	echo '</pre>';
}

/**
* Get data from a URL
*
* @param String $url to get data from
* @return String of data
*/
function get_data_url($url) {
	$ch = curl_init();
	
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	
	return $data;
}
?>