<?php

/**
 * Model Class
 * 
 * Interact with database tables.
 * 
 * The Model class is an abstract class that facilitates
 * database operations. Each child class represents a
 * database table, and their instances represent
 * database rows.
 */

namespace Sturdy;

require_once "Database.php";

abstract class Model{

	public $id;

	/**
	 * Unset default property values.
	 */
	function __construct($fields = []){

		foreach($fields as $key => $value) $this->$key = $value;

		/*
		foreach($this as $property => $value){

			$this->property = $fields[$property] ?? null;
		}
		*/
	}

	/**
	 * Prevent property definition in runtime.
	 * 
	 * @throws Exception
	 */
	function __set($name, $value){

		throw new Exception("Fields \$$name does not exist in ".self.".");
	}

	/**
	 * Create database record. Update if already exists.
	 * 
	 * @return mysqli_result|bool
	 */
	function save(){

		$data = (array)$this;
		$data = array_filter($data, fn($x) => isset($x)); //filter out unset properties
		unset($data["id"]); //omit id
		if(isset($this->id)){ //update if exists

			$query = "UPDATE ".get_class($this)." SET ";
			
			foreach($data as $key => $value){

				if(isset($value)){

					$query .= $key." = '$value'";
					if($key == array_key_last($data)) $query .= " ";
					else $query .= ", ";
				}
			}

			$query .= " WHERE id = $this->id;";
			$result = Database::getInstance()->query($query);
		}
		else{ //create if does not exist
		
			$query = "INSERT INTO ".get_class($this)." (".implode(", ", array_keys($data)).") VALUES ('".implode("', '", array_values($data))."');";
			$result = Database::getInstance()->query($query);
			$this->id = Database::getInstance()->getLastInsertID();
		}
		return $result;
	}

	/**
	 * Delete the record.
	 */
	function delete(){

		$query = "DELETE FROM ".get_class($this)." WHERE id = $this->id;";
		Database::getInstance()->query($query);
	}

	/**
	 * Delete records satisfying condition.
	 * 
	 * @param string $condition Condition in SQL format
	 */
	static function deleteWhere($condition){

		$query = "DELETE FROM ".get_called_class()." WHERE $condition;";
		Database::getInstance()->query($query);
	}

	/**
	 * Fetch the record satisfying condition.
	 * 
	 * @param string $condition Conditions in SQL format
	 * @param string $fields Property names separated by commas
	 * 
	 * @throws Exception
	 * 
	 * @return Model|null
	 */
	static function get($condition, $fields = "*"){
		
		$query = "SELECT $fields FROM ".get_called_class()." WHERE $condition;";
		$result = Database::getInstance()->fetch($query, MYSQLI_ASSOC);
		if($result){

			if(count($result) > 1) throw new Exception("Multiple results from Model::get()."); 
			else return new (get_called_class())($result[0]);
		}
		return null;
	}

	/**
	 * Fetch all records satisfying condition.
	 * 
	 * @param string $condition Condition in SQL format
	 * @param string $fields Property names separated by commas
	 * 
	 * @return array Array of instances or empty array
	 */
	static function filter($condition = "", $fields = "*"){

		if($condition) $condition = " WHERE ".$condition;
		$query = "SELECT $fields FROM ".get_called_class()."$condition;";
		$result = Database::getInstance()->fetch($query, MYSQLI_ASSOC);
		if($result) return array_map(fn($x) => new (get_called_class())($x), $result); 
		return [];
	}

	/**
	 * Fetch all records.
	 * 
	 * @param string $fields Property names separated by commas
	 * 
	 * @return array Array of instances or empty array
	 */
	static function all($fields = "*"){

		return self::filter(fields: $fields);
	}

	/**
	 * Check if a record satisfying condition exists.
	 * 
	 * @param string $condition Condition in SQL format
	 * 
	 * @return bool
	 */
	static function exists($condition){

		$query = "SELECT EXISTS(SELECT * FROM ".get_called_class()." WHERE $condition LIMIT 1);";
		$result = Database::getInstance()->fetch($query);
		return $result[0][0];
	}
}
