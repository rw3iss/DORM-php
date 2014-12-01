<?php
namespace Dorm;

/**
 * /dorm/lib/core/dorm_router.php - Basic routing of requests. Will attempt to fulfill the current
 * request against any database page routes, which are overriden by static routes 
 * defined in dorm_routes.php
 * TODO: Needs to be re-written...
 */

/* Load site and admin static routes */
require_once(DORM_PATH . '/lib/dorm_routes.php');

class DormRouter {
	public $uri = ''; // the current request URI
	public $request; //the current DormRequest, wrapper for uri, type, and data
	public $routes; // routes loaded from config file
	public $route; // the current route if matched

	/** 
	 * Public entry point for routing a URI request. It will populate the DormRequest object
	 * with the given uri, type, and data, or otherwise automatically detect them.
	 * @param string $uri If provided, will route the current request to the specified uri,
	 *        otherwise will use current REQUEST_URI.
	 * @param string $type If provided, will set the current request to the type, ie. GET/POST, etc.
	 * @param string $data If provided, will fill the current request data to be used elsewhere.
	 * @return DormRequest Encapsulates the request uri, type, and data details.
	 */
	public function routeUri($uri = null, $type = null, $data = null) {
		/* Try to use the current request URI if none is provided */
		if($uri == null) {
			if ( isset($_SERVER['REQUEST_URI']) ){
				$uri = $_SERVER['REQUEST_URI'];
			}
		}

		if($uri == null)
			throw New Exception("No URI to route.");

		/* Encapsulate the current URI request details */
		$request = new Models\DormRequest();

		/* Fill the request details with the given request data */
		$request->populate($uri, $type, $data);

		$this->routeRequest($request);
	}

	/**
	 * Public entry point for routing a DormRequest object, which encapsulates a URI, a request type, 
	 * and request data. Tries to match the given DormRequest to a route. If none is provided, it will
	 * try to use the current DormRouter->request object, if available.
	 * @param DormRequest $request Encapsulation of the request details to route.
	 * @return DormRequest Encapsulates the request uri, type, and data details.
	 */
	public function routeRequest($request = null) {
		if($request == null) {
			if($this->request != null) 
				$request = $this->request;
			else
				throw new DormException("Cannot route a null request.");
		} else {
			$this->request = $request;
		}

		/* Prepare the existing routes */
		$this->_initRouting();

		$this->uri = $request->uri;

		/* Try to match and execute the request through a route */
		$this->route = $this->_matchRoute($request);

		/* We have a matched route, now try to fulfill the request through it */
		$this->_tryRoute($this->route);

		return $request;
	}

	/**
	 * Primarily ensures that the static routes are loaded.
	 */
	private function _initRouting() {
		global $dorm_routes;

		$this->routes = ( ! isset($dorm_routes) OR ! is_array($dorm_routes)) ? array() : $dorm_routes;

		$this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? FALSE : strtolower($this->routes['default_controller']);
	}

	/**
	 * Attempts to match the given request URI to a route. First it will check 
	 * static routes defined locally. If none match, it will attempt to find a
	 * route to a Page in the database. If none still match, it will use the 
	 * default route.
	 *	@return DormRoute The matching route to fulfill the request.
	 */
	private function _matchRoute($request) {
		$route = $this->_matchStaticRoute($request);

		if(!$route) 
			$route = $this->_match_page_route($request);

		if(!$route)
			$route = $this->_match_default_route($request);

		if(!$route) 
			throw new DormException("A route could not be found for the current request URI: " . $request->uri);

		return $route;
	}

	private function _matchStaticRoute($request) {
		$route = null;

		//First, try to match a literal static route
		if (isset($this->routes[$request->uri]))
		{
			$routePath = $this->routes[$request->uri];

			$route = new Models\DormRoute(Models\RouteType::STATIC_ROUTE, $request->uri);
			$route->routePath = $routePath;
			$route->request = $request;
		}

		//If no literal is found, try to match a regexp static route
		// Loop through the route array looking for wild-cards
		foreach ($this->routes as $key => $val)
		{
			// Convert wild-cards to RegEx
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));

			// Does the RegEx match?
			if (preg_match('#^'.$key.'$#', $route->routePath) || preg_match('#^'.$key.'$#', '/' . $route->routePath) )
			{
				// Do we have a back-reference?
				if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
				{
					$val = preg_replace('#^'.$key.'$#', $val, $uri);
				}

				debug("matching wildcard route", $val);
				//return $this->_set_request(explode('/', $val));
			}
		}

		return $route;
	}

	private function _match_page_route($request) {
		throw new DormException("NOT IMPLEMENTED");

		$route = new Models\DormRoute();
		$route->request = $request;
	}

	private function _match_default_route($request) {
		throw new DormException("NOT IMPLEMENTED");

		$route = new Models\DormRoute();
		$route->request = $request;
	}

	private function _tryRoute($route) {
		/* Validate the route, setting the directory, class, and method that will fulfill the request */
		$this->_validateRoute($route);

		/* We've established the route, now proceed to executing the request */
		$this->_executeRoute($route);
	}

	//Helper
	private function _explodeSegments($routePath) {
		$segments = array();

		foreach (explode("/", preg_replace("|/*(.+?)/*$|", "\\1", $routePath)) as $val)
		{
			// Filter segments for security
			$val = trim($this->_filterUri($val));

			if ($val != '')
			{
				$segments[] = $val;
			}
		}

		return $segments;
	}

	/* filter_uri() - Filters malicious characters out of the uri */
	private function _filterUri($str)
	{
		global $dorm;

		if ($str != '' && $dorm->config['permitted_uri_chars'] != '' ) //&& $this->config->item('enable_query_strings') == FALSE)
		{
			// preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
			// compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
			if ( ! preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote($dorm->config['permitted_uri_chars'], '-'))."]+$|i", $str))
			{
				throw new DormException('The URI you submitted has disallowed characters.');
			}
		}

		// Convert programatic characters to entities
		$bad	= array('$',		'(',		')',		'%28',		'%29');
		$good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');

		return str_replace($bad, $good, $str);
	}

	/**
	 * Checks that the controller path and method exist to handle the current request.
	 * If the request is valid, it will set the directory, file, and method names in the route.
	 */
	function _validateRoute($route)
	{
		global $dorm;

		/* Explode the current route path into segments for validation */
		$segments = $this->_explodeSegments($route->routePath);

		//if more than two segments, match the segments directly to a path
		$numSegments = sizeof($segments);

		if($numSegments > 2) {
			//Build a path based on the segments
			$p = DORM_PATH . '/';

			for($i = 0;$i < $numSegments-2;$i++) {
				$p .= $segments[$i] . '/';
			}

			$c = $segments[$numSegments-2];
			$m = $segments[$numSegments-1];

			if (file_exists($p.$c.'.php'))
			{	
				$route->directory = $p;
				$route->class = $c;
				$route->method = $m;

				return $segments;
			}
		} else {
			//otherwise, match the segments directly to a controllers folder
			if($numSegments == 2) {
				$p = DORM_PATH . '/controllers/';

				$c = $segments[$numSegments-2];
				$m = $segments[$numSegments-1];

				// Does the controller exist in this hard-coded route path
				if (file_exists($p.$c.'.php'))
				{	
					$route->directory = $p;
					$route->class = $c;
					$route->method = $m;

					debug("Route directly to controller (DormRouter:263)", $p.$c.'.php');

					return $segments;
				}
			} else {
				//load the default controller and check that the given method exists
				throw new DormException("Default controller not implemented, DormRouter:268");
			}
		}

		throw new DormException("File not found for matching route. This is a 404.");
	}

	/**
	 * Attempts to execute the given route by calling the appropriate handler class.
	 * @param DormRoute $route The encapsulated route details to handle. 
	 */
	private function _executeRoute($route) {
		$file = $route->directory . $route->class . '.php';

		if(!file_exists($file)) {
			throw new DormException("The route path's file does not exist: " . $file);
		}

		//We've already validated the existence of the file
		require_once($file);

		//instantiate the class
		$routeClass = new $route->class;

		if(!method_exists($routeClass, $route->method)) {
			throw new DormException("The route's method does not exist in the file. File: " . 
				$file . ". Method: " . $route->method);
		}

		$routeClass->{$route->method}();
	}

	function _set_default_controller()
	{
		if ($this->default_controller === FALSE)
		{
			$this->dorm->response->error_response("Unable to determine what should be displayed	. A default route has not been specified in the routing file.");
		}

		// Is the method being specified?
		if (strpos($this->default_controller, '/') !== FALSE)
		{
			$this->set_class($x[0]);
			$this->set_method($x[1]);
			$this->_set_request($x);
		}
		else
		{
			$this->set_class($this->default_controller);
			$this->set_method('index');
			$this->_set_request(array($this->default_controller, 'index'));
		}

		// re-index the routed segments array so it starts with 1 rather than 0
		//b$this->uri->_reindex_segments();

		log_message('debug', "No URI present. Default controller set.");
	}

	function _detect_uri() {
		if ( ! isset($_SERVER['REQUEST_URI']) OR ! isset($_SERVER['SCRIPT_NAME']))
		{
			return '';
		}

		$uri = $_SERVER['REQUEST_URI'];

		if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
		{

			$uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
		}
		elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
		{
			//chop of trailing slash?
			if(substr($uri, -1) == '/') {
			    //$uri = substr($uri, 0, -1);
			}
			//$uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
		}

		// This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
		// URI is found, and also fixes the QUERY_STRING server var and $_GET array.
		if (strncmp($uri, '?/', 2) === 0)
		{ 
			$uri = substr($uri, 2);
		}

		$parts = preg_split('#\?#i', $uri, 2);
		$uri = $parts[0];

		if (isset($parts[1]))
		{
			$_SERVER['QUERY_STRING'] = $parts[1];
			parse_str($_SERVER['QUERY_STRING'], $_GET);
		}
		else
		{
			$_SERVER['QUERY_STRING'] = '';
			$_GET = array();
		}

		if ($uri == '/' || empty($uri))
		{
			return '/';
		}

		$uri = parse_url($uri, PHP_URL_PATH);

		// Do some final cleaning of the URI and return it
		return str_replace(array('//', '../'), '/', rtrim($uri, '/'));
	}

}