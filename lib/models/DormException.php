<?php
namespace Dorm; //using base namespace for convenience

class DormException extends \Exception {
	public function __tostring() {
		return $this->message;
	}
}

?>