<?php
namespace Dorm\Models;

/**
 * /dorm/lib/core/dorm_request.php - Encapsulates details about the current request.
 *
 */

class DormRequest {
	public $type = null;
	public $uri = null;
	public $data = null;

	/**
	 * Helper method to populate the type, uri, and data parameters for the current request.
	 *
	 */
	public function populate($uri = null, $type = null, $data = null) {
		if($type == null)
		$this->uri = $uri ?: $_SERVER["REQUEST_URI"];
		$this->type = $type ?: $_SERVER["REQUEST_METHOD"] ?: 'GET';
		$this->data = $data;
	}
}

abstract class RequestType {
	const GET = "GET";
	const POST = "POST";
	const PUT = "PUT";
	const DELETE = "DELETE";
}

?>