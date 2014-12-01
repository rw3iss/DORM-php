<?php
/**
 * /dorm/lib/core/dorm_helper.php - Contains auxiliary methods for general functionality not related specifically
 *
 */

/* Shorthand helper to retrieve the global Dorm instance */
function dorm() {
	global $dorm;
	return $dorm;
}

function dorm_is_installed() {
	return false;
}

function dorm_redirect($uri) {
	header('Location: ' . $uri);
}

/* log to a file */
function log_message($type, $msg) {
}

/* FOR DEBUGGING ONLY */
ini_set('display_errors', '1');

function debug() {
	$args = func_get_args();

	foreach($args as $a) {
		if(is_object($a) || is_array($a)) {
			echo print_r($a) . ' ';
		} else {
			echo $a . ' ';
		}
	}

	//add a line break
	if(php_sapi_name() == "cli") {
		echo "\n";
	} else {
		echo "<br/>";
	}
}


?>