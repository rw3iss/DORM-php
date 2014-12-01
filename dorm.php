<?php
namespace Dorm;

/* Global Dorm path */
define('DORM_PATH', realpath(dirname(__FILE__)));

/* global instance of dorm */
$dorm = null;

/* Include the DormLoader to load Dorm */
require_once(DORM_PATH . '/lib/DormLoader.php');

/* Load Dorm */
DormLoader::load();

?>