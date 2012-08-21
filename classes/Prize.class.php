<?php
//=============================================================================
// Class: Prize
// Purpose: Stores all relevant data and functions of a prize
//=============================================================================
class Prize {

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
				FROM nuprizes
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
	* Load all prize ids
	*
	* @return Array of prize ids
	*/
	public function loadPrizeIds() {
		global $config;
		$pids = array();
		
		$result = $config['database']->query("
			SELECT id
			FROM nuprizes
			ORDER BY points_req ASC 
		");
		
		while($row = $result->fetch_row()){
			$pids[] =$row[0];
		}

		return $pids;
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
			FROM nuprizes
			WHERE id = {$this->data['id']}
			LIMIT 1
		");
		
		return $result->num_rows;
	}
	
}
?>