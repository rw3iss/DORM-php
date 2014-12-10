<?php
namespace Dorm;

/* Global Dorm path, change this if you want */
define('DORM_PATH', realpath(dirname(__FILE__)));

/* Include DormLoader to load Dorm */
require_once(DORM_PATH . '/lib/DormLoader.php');

/* Load Dorm */
DormLoader::load();

?>