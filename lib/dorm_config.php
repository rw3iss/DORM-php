<?php

/* Include the public site config */
require_once(DORM_PATH . '/dorm_config.php');

/**
 * namespacePaths - defines the internal namespaces used by Dorm. 
 * Do not modify this.
 *
 * @var array
 */
$namespacePaths = array(
	'Dorm' 			=> array(DORM_PATH . '/lib', DORM_PATH . '/lib/models'),
	'Dorm\Models' 	=> DORM_PATH . '/lib/models',
	'Dorm\Controllers' 	=> DORM_PATH . '/lib/controllers',
	'Dorm\Plugins' 	=> DORM_PATH . '/lib/plugins',
);

/* permitted_uri_chars - Defined which characters are allowed on URL requests to the system.
 *
 */
$dorm_config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

?>