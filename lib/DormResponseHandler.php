<?php
namespace Dorm;

/**
 * /dorm/lib/core/dorm_response.php - Offers means of communicating back to the client, 
 * providing a set of method to encapsulate data and set any required headers. Supports
 * rendering of views and 
 * TODO: implement as Response interfaces:
 *   ResponseHandler->respond($result)
 * ie: JsonResponseHandler->respond($result); prints json
 * ie: ViewResponseHandler->respond($view); prints the view to the browser
 * TODO: needs to be cleaned up
 */

class DormResponseHandler {
	private $final_output = '';

	function view($view, $return = false) {
		//serve the view depending on which environment we're in
		$view_path = DORM_PATH . 'views/' . $view . '.php';

		if ( ! file_exists($view_path))
		{
			//try a default view
			$view_path = DORM_PATH . 'views/default/' . $view . '.php';

			if ( ! file_exists($view_path)) {
				//TODO: throw error
				throw new Exception("404: Page not found: " . $view);
			}
		}

		return $this->_load_view($view_path, $return);
	}

	// Add the view directly to the current buffer output, in place.
	function insert($view) {
		//serve the view depending on which environment we're in
		$viewPath = DORM_PATH . (strpos($view, '/') == 0 ? '' : '/') . $view;

		if (strpos($view,'.') === FALSE) {
			$viewPath .= '.php';
		}

		if ( ! file_exists($viewPath))
		{
			throw new DormException("Could not locate the view file for inclusion: " . $viewPath);
		}

		$this->_load_view($viewPath, false);
	}

	//Loads a view based on an absolute path
	function system_view($view, $return = false) {
		//serve the view depending on which environment we're in
		$view_path = DORM_PATH . '/lib/views/';

		if(strpos($view, '/') == false) {
			//$mobile = $dorm->MobileDetect->isMobile();
			$mobile = FALSE; //TODO

			if($mobile) 
				$view_path .= 'mobile/';
			else 
				$view_path .= 'default/';
		}

		$view_path .= $view;

		if (strpos($view,'.') === FALSE) {
			$view_path .= '.php';
		}

		//try the shared folder if this one doesn't exist
		if ( ! file_exists($view_path))
		{
			$view_path = DORM_PATH . '/lib/views/shared/' . $view;

			if (strpos($view,'.') === FALSE) {
				$view_path .= '.php';
			}
		}

		return $this->_load_view($view_path, $return);
	}

	function responsive_view($view, $return = false) {
		//serve the view depending on which environment we're in
		$view_path = DORM_PATH . '/views/';

		//if view contains a path, user wants to load it manually, so start from the views folder,
		//otherwise detect if we're to be responsive and load the appropriate view.
		if(strpos($view, '/') == false) {
			//$mobile = $dorm->MobileDetect->isMobile();
			$mobile = FALSE;

			if($mobile) 
				$view_path .= 'mobile/';
			else 
				$view_path .= 'default/';
		}

		$view_path .= $view;
		if (strpos($view,'.') === FALSE) {
			$view_path .= '.php';
		}

		return $this->_load_view($view_path, $return);
	}

	function json_response($o) {
		echo json_encode($o);
	}

	function set_header($header, $value) {
		header($header, $value);
	}

	// Returns a standard json error response for backend requests.
	function error_response($message, $reponse_code = 400, $o = null) {
		$msg = "";
		if(is_array($message)) {
			$delim = '';
			foreach($message as $m) {
				$msg .= $delim . $m;
				$delim = ', ';
			}
		} else {
			$msg = $message;
		}

		$data = array("success" => false, "error" => $reponse_code, "message" => $msg);
		if($o != null)
			$data['object'] = $o;

		echo json_encode($data);
		exit();
	}

	// Adds content directly to the current output
	function append_output($output)
	{
		if ($this->final_output == '')
		{
			$this->final_output = $output;
		}
		else
		{
			$this->final_output .= $output;
		}

		return $this;
	}

	// Actually outputs the output
	private function _display($output = '') {
		global $dorm;

		//TOOD: implement caching of output

		// Does the current controller contain a function named _output()?
		// If so send the output there.  Otherwise, echo it.
		if(isset($dorm->controller)) {
			if (method_exists($dorm->controller, '_output'))
			{
				$dorm->controller->_output($output);
				return;
			}
		}

		// Send it to the browser!
		echo $output;
	}

	// Does the loading work. If return is true, it will return the view as a string instead of rendering it.
	private function _load_view($view_path, $return) {
		global $dorm;

		if ( ! file_exists($view_path))
		{
			$this->error_response("File not found: " . $view_path, 404);
		}

		ob_start();
		
		include($view_path);

		if ($return === TRUE)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		if (ob_get_level() > $dorm->_dorm_ob_level + 1)
		{
			ob_end_flush();
		}
		else
		{
			$this->append_output(ob_get_contents());
			@ob_end_clean();
		}

		$this->_display($this->final_output);
	}

}

?>