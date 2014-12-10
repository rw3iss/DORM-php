<?php

class DefaultController extends \Dorm\Models\DormController {

	function index() {
		dorm()->response->responsive_view('index');
	}

	/* responds with a partial view  */
	function partial() {
		global $dorm;

		//TODO: better way to get partial lastname
	 	$view = $dorm->router->uri_segments[1];
		
		$dorm->response->responsive_view('partials/'. $view);
	}

	function page_not_found() {
		echo "PAGE NOT FOUND";
	}
	
}

?>