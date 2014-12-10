<?php
namespace Dorm;

/**
* /dorm/lib/core/dorm_loader.php - Contains all base methods for loading and initializing the Dorm framework.
*
*/

// Is the Dorm path set correctly?
if ( ! defined('DORM_PATH') || ! is_dir(DORM_PATH))
{
	exit("Error obtaining Dorm's directory path. Please ensure that /dorm/dorm_config.php 
		is included in your script. If it is, please open and correct the DORM_PATH variable.");
}

/* Include the internal configuration */
require_once(DORM_PATH . '/lib/dorm_config.php');

/* Helper methods for convenience */
require_once(DORM_PATH . '/lib/dorm_helpers.php');

/* Include the AutoLoader class */
require_once(DORM_PATH . '/lib/DormAutoLoader.php');

/* Start the AutoLoader */
$loader = new DormAutoLoader();

/* Register the mapping of Dorm namespaces to their file paths */
$loader->registerNamespaces($namespacePaths);


/**
 * Main Loader class which initializes and bootstraps Dorm.
 *
 */
class DormLoader extends Models\DormSingleton {

	/**
	 * Initializes and bootstraps the Dorm object.
	 * @return Instance of the global Dorm object.
	 */
	public static function load() {
		global $dorm;
		global $dorm_config;

		if($dorm != null) {
			throw new DormException("Dorm is already loaded.");
		}

		//global Dorm object which will encapsulate all underlying plugins and libraries
		$dorm = new Models\Dorm();

		//store system configuration
		$dorm->config = $dorm_config;

		/**
		 * First, setup basic faculties that don't require a Dorm intallation
		 * and then check for the Dorm installation 
		 */

		//Setup the router
		$dorm->router = new DormRouter();

		//Setup basic response handlers
		$dorm->response = new DormResponseHandler();

		//Setup basic error handlers
		$dorm->error_handler = new DormErrorHandler();

		/* Gather the current request details */
		$request = new Models\DormRequest();
		$request->populate();

		/* Store the current request details */
		$dorm->request = $request;

		/* Basic setup is finished, now move on to building and request fulfillment */

		//First, check if Dorm is installed
		if(!dorm_is_installed()) {
			if($request->uri != '/dorm/install')
				dorm_redirect('/dorm/install');
			else
				$dorm->router->routeRequest($request);

			return;
		}  else {
			if($request->uri != '/dorm/install')
				dorm_redirect('/dorm');
		}

		/* Basic setup is finished, now move on to building and request fulfillment */
		self::_buildDorm();

		$dorm->router->routeRequest($request);

		return $dorm;
	}

	/**
	 * Populates the global Dorm object with necessary libraries.
	 * TODO: Pull this object as a singleton from a Cache.
	 */
	private static function _buildDorm() {
		global $dorm;
		global $dorm_config;
		global $dorm_routes;

		$dorm->cache = new DormCache();

		// Init and load any requested autoload plugins
		foreach($dorm_config['autoload_plugins'] as $plugin) {
			$pluginPath = DORM_PATH . '/plugins/' . $plugin;

			if(!file_exists($pluginPath)) {
				die('Could not find plugin to autoload in the /dorm/plugins directory: ' . $pluginPath);
			} else {
				if( isset($dorm->{$plugin}) ) {
					//The plugin is conflicting with another library.
					die("Error: Object '" . $plugin . "' is already defined on the Dorm object. 
						The plugin should use another name. You can correct this by changing the 
						plugin's folder name at: " . $pluginPath . ", the plugin's class name
						defined in the plugin.php file, and also the autoload name defined in: " . 
						DORM_PATH . "/dorm_config.php");
				} else {
					//Okay, include the plugin's main plugin.php file
					$path = DORM_PATH . '/plugins/' . $plugin . '/plugin.php';
					require_once($path);

					//Initialize the plugin object, first trying without a namespace:
					try {
						$po = new $plugin();
					} catch(Exception $ex) {
						$pluginName = "\Dorm\Plugins" . $plugin;
						$po = new $pluginName();
					}

					//If the plugin defines a specific key, we will use this to reference the plugin
					//on the Dorm object. Otherwise, we just reference the plugin usings its name.
					if($po->plugin_key != '') {
						if( isset($dorm->{$po->plugin_key}) ) {
							die("Error: The plugin '" . $plugin . "' defines a plugin_key which is
								already in use: '" . $po->plugin_key . ". Please change the
								plugin_key name in the plugin.php file located at: " . $pluginPath);
						} else {
							$dorm->{$po->plugin_key} = $po;
						}
					} else {
						//Reference the plugin using its name
						$dorm->{$plugin} = $po;
					}

					//Tell the plugin to initialize itself
					$po->on_load();
				}
			}
		}

		//Dorm is now built.
		return $dorm;
	}

}

?>