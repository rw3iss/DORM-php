<?php
/**
 * DORM_PATH - Can override this to the /dorm directory, wherever it resides on your 
 * server, ie. if you wanted to use an external Dorm instance across multiple sites.
 */

$dorm_config = array();

/**
 * autoload_plugins - An array of plugins that will automatically be loaded at the 
 * beginning of every request.
 */
$dorm_config['autoload_plugins'] = array( 'Session', 'MobileDetect', 'Users', 'Data' );

/**
 * rest_prefix - The url prefix that will be used to route requests to the rest
 * controller. ie. yoursite.com/api/m/User/1 will retrieve User record with id=1
 */
$dorm_config['rest_prefix'] = 'api';

/**
 * cache_enabled - Will cache all of the dorm libraries that it can, including
 * the global Dorm object and its loaded plugins, so that they don't have to be 
 * initialized upon every request 
 */
$dorm_config['cache_enabled'] = TRUE; 

/**
 * use_session - Will store informatio pertaining to the current request session.
 * It will use a cookie to keep track of the session identifier for the current user.
 * If session_use_database is true, it will store all session information in the 
 * database so that it can be used across multiple servers sharing the same database.
 */
$dorm_config['use_sessions'] = TRUE;
$dorm_config['session_cookie_name']	 = 'dorm_session';
$dorm_config['session_expiration']	 = 7200; //when the cookie will automatically destruct, 0 = forever
$dorm_config['session_expire_on_close']	= FALSE;
$dorm_config['session_use_database']	= FALSE;
$dorm_config['session_table_name']	 = 'dorm_sessions';
$dorm_config['session_time_to_update']	= 300;


?>