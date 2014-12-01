<?php
/**
 * /dorm/lib/core/dorm_routes.php - Defined static routes used internally by the dorm system.
 *
 */

/* Include the publically defined static routes */
require_once(DORM_PATH . '/dorm_routes.php');

global $dorm_routes;

/* Create static routes for specific dorm backend requests */
$dorm_routes['/dorm'] = '/lib/controllers/dormadmincontroller/index';
$dorm_routes['/api'] = '/lib/controllers/dormrestcontroller/request';

$dorm_routes['/dorm/install'] = '/lib/controllers/dormadmincontroller/install';

?>
