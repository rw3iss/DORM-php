<?php
namespace Dorm\Models;

/* DormPlugin - Base class describing the interface for basic plugin communication with the Dorm system. */

class DormPlugin {
	public $plugin_key = ''; //How the plugin will be referenced on the Dorm object

	public $plugin_name = '';

	public $plugin_version = '';

	public function on_load() {
	}
}