<?php
//=============================================================================
// Class: Achievement
// Purpose: Stores all relevant data and functions of an achievement
//=============================================================================
class Achievement {

	private $data = array();
	
	/**
	* Constructor
	*
	* @param Array $args optional parameters
	*	id: Integer
	*/
	public function __construct($args = null) {
		$default = array(
			'id' => null
		);
		
		if($args)
			$params = array_merge($default, $args);
		else
			$params = $default;
		
		foreach($params as $key => $val){
			$this->data[$key] = $val;
		}
		
		if(!empty($this->data['id']))
			$this->load();
	}
	
	/**
	* Set a value
	*
	* @param String $name
	* @param Mixed $value
	*/
	public function __set($name, $value) {
		$this->data[$name] = $value;
	}
	
	/**
	* Get a value
	*
	* @param String $name value to get
	* @return Mixed value
	*/
	public function __get($name) {
		if(array_key_exists($name, $this->data))
			return $this->data[$name];
		else
			return null;
	}
	
	/**
	* Get this data
	*
	* @return Array data
	*/
	public function getData() {
		return $this->data;
	}
	
	/**
	* Load data for this
	*
	* @return Boolean
	*/
	public function load() {
		global $config;
		
		if($this->exists()){
			$result = $config['database']->query("
				SELECT *
				FROM nuachievements
				WHERE id = {$this->data['id']}
				LIMIT 1
			");
			$row = $result->fetch_assoc();
			
			foreach($row as $key => $val){
				$this->data[$key] = $val;
			}
			
			return $result;
		}else{
			$this->data['id'] = null;
			return false;
		}
	}
	
	/**
	* Check if this exists
	*
	* @param Integer $id
	* @return Boolean
	*/
	public function exists() {
		global $config;
		
		$result = $config['database']->query("
			SELECT id
			FROM nuachievements
			WHERE id = {$this->data['id']}
			LIMIT 1
		");
		
		return $result->num_rows;
	}
	
}
?>