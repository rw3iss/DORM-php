<?php

class DormAdminController extends \Dorm\Models\DormController {

	function index() {
		dorm()->response->system_view('dorm_manage');
	}

	function install() {
		global $dorm;

		if(dorm_is_installed()) {
			debug("ALREADY INSTALLED");
		}

		debug("INSTALL", $dorm->request->type);

		switch($dorm->request->type) {
			case 'GET':
				$dorm->response->system_view('dorm_install');
				break;
			case 'POST':
				$this->_do_install();
				$dorm->response->system_view('dorm_install_result');
				break;
			default:
				$dorm->response->error_redirect(Error::PAGE_NOT_FOUND);
		}
	}

	private function _do_install() {
		debug("installing");
	}
}

?>