<?php

require_once('lib/dorm_library.php');

class Data extends \Dorm\Models\DormPlugin {
	public $plugin_key = "DDD";

	function test() {
		echo "Test!";
	}
}


?>