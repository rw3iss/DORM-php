<?php
namespace Dorm\Models;

/**
 * A container for the current route request details;
 */

class DormRoute {
	/* RouteType for the route */
	public $type;

	/* The uri/route key for the current request */
	public $uri;

	/* The specific static route value or page the route points to */
	public $routePath;

	/* If the route is valid, this will be the directory of the containing class */
	public $directory;

	/* If the route is valid, this will be the class that will execute the request */
	public $class;

	/* If the route is valid, this will be the method that will execute the request */
	public $method;

	/* The current DormRequest details */
	public $request = null;

	public function __construct($type, $uri) {
		$this->type = $type;
		$this->uri;
	}
}

abstract class RouteType {
	const STATIC_ROUTE = "STATIC";
	const PAGE_ROUTE = "PAGE";
	const DEFAULT_ROUTE = "DEFAULT";
}

?>