<?php
/**
* /dorm/dorm_routes.php - This page is used to define static routes for the site. These will override 
* any routes that are defined within the Dorm database through pages.
*
*/

global $dorm_routes;

//Set a default catch for non-implemented routes
$dorm_routes['default_route'] = 'defaultcontroller/pagenotfound';


//Home page
$dorm_routes['/'] = 'defaultcontroller/index';


//Partial templates/pages
$dorm_routes['/partial/(:any)'] = 'defaultcontroller/partial';


//API routes
$dorm_routes['/api/user/(:any)'] = 'apicontroller/user';

?>