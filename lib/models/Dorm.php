<?php
namespace Dorm\Models;

/**
 * /dorm/lib/core/models/dorm.php - Serves as the base application container, which 
 * encapsulates all Dorm behavior, providing access to all plugins, models, etc.
 * These can then be accessed from within a script that has loaded Dorm by, for
 * example:  
 *   $dorm->pluginname->pluginfunction();  //call a loaded plugin's function
 *   $dorm->data->get('ModelName', {property: value});  //get a concrete data object
 *   $dorm->data->get_type('ModelName');  //get the actual model definition object
 */

class Dorm {
	function __construct() {
		//Store the current output buffer level, to support nested rendering of view data
		//WHAT THE FUCK IS THIS?
		$this->_dorm_ob_level  = ob_get_level();
	}

	function initialize() {
	}
}


?>