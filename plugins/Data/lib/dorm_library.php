<?php

/* DormLibrary - This is the main ORM manager for Dorm data */
class DormLibrary {

	function __construct() {

	}

	/* returns all models or filtered by the gives id */
	function get_model_definitions($ident = null) {
		$TP = self::$TABLE_PREFIX;
		$conn = db_conn();

		$models = array();

		//TODO: does model exist? 
		//TODO: get mapping of model type to db table name (later)
		$sql = "SELECT * FROM " . $TP . "models"; //todo: figure out how to handle the pluralization

		//get a specific model definition?
		if($ident != null) {
			if(is_numeric($ident) === true) {
				$sql .= " WHERE id=" . $ident;
			} else {
				$sql .= " WHERE name='" . $ident ."'";
			}
		}

		$result = $conn->Execute($sql);

		while (!$result->EOF) {
			$mdef = new ModelDefinition();
			array_push($models, $mdef);

			$result->MoveNext();
		}

		$conn->Close();

		return $models;
	}

}

?>