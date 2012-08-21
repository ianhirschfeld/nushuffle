<?php
//=============================================================================
// Class: Rating
// Purpose: Stores all relevant data and functions of a rating
//=============================================================================
class Rating {

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
				FROM nuratings
				LEFT JOIN nushuffles ON nuratings.shuffle_id = nushuffles.id
				WHERE nuratings.id = {$this->data['id']}
				LIMIT 1
			");
			$row = $result->fetch_assoc();
			
			foreach($row as $key => $val){
				if(!array_key_exists($key, $this->data)){
					if(is_string($val)){
						if(!get_magic_quotes_gpc())
							$this->data[$key] = stripslashes($val);
						else
							$this->data[$key] = $val;
					}else{
						$this->data[$key] = $val;
					}
				}
			}
			
			$this->data['time_passed'] = $this->loadTimePassed();
			$this->data['replies'] = $this->loadReplies();
			
			return $result;
		}else{
			$this->data['id'] = null;
			return false;
		}
	}
	
	/**
	* Calculate time passed since submitted
	*
	* @return String of time passed
	*/
	public function loadTimePassed() {
		$timepassed = '';
		$submitted = strtotime($this->data['date_submitted']);
		$time = time() - $submitted;
		$ranges = array(
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hour',
			60 => 'minute',
			1 => 'second'
		);
		
		if($time != 0){
			foreach($ranges as $range => $text){
				if($time < $range) continue;
				$numberOfUnits = floor($time / $range);
				$timepassed = $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' ago';
				break;
			}
		}else{
			$timepassed = 'right now';
		}
		
		return $timepassed;
	}
	
	/**
	* Load the replies
	*
	* @return Array replies
	*/
	public function loadReplies() {
		global $config;
		$replies = array();
		
		$result = $config['database']->query("
			SELECT *
			FROM nureplies
			WHERE rating_id = {$this->id}
				AND enabled = 1
			ORDER BY date_submitted ASC
		");
		
		while($row = $result->fetch_assoc()){
			$timepassed = '';
			$submitted = strtotime($row['date_submitted']);
			$time = time() - $submitted;
			$ranges = array(
				31536000 => 'year',
				2592000 => 'month',
				604800 => 'week',
				86400 => 'day',
				3600 => 'hour',
				60 => 'minute',
				1 => 'second'
			);
			
			if($time != 0){
				foreach($ranges as $range => $text){
					if($time < $range) continue;
					$numberOfUnits = floor($time / $range);
					$timepassed = $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' ago';
					break;
				}
			}else{
				$timepassed = 'right now';
			}
			$row['time_passed'] = $timepassed;
			
			$replies[] = $row;
		}
		
		return $replies;
	}
	
	/**
	* Load rating ids
	*
	* @param Array optional params
	* @return Array rating ids
	*/
	public function loadRatingIds($args = null) {
		global $config;
		$rids = array();
		
		$default = array(
			'num' => 10,
			'page' => 1,
			'did' => null,
			'uid' => null
		);
		
		if($args)
			$params = array_merge($default, $args);
		else
			$params = $default;
		
		extract($params);
		
		if($num > 0){
			$start = $num * ($page - 1);
			$limit = 'LIMIT '.$start.', '.$num;
		}else{
			$limit = '';
		}
		
		if(!empty($did))
			$and = "AND dept_id = $did";
		else if(!empty($uid))
			$and = "AND user_id = $uid";
		else
			$and = '';
	
		$result = $config['database']->query("
			SELECT id
			FROM nuratings
			WHERE enabled = 1
			$and
			GROUP BY shuffle_id
			ORDER BY date_submitted DESC
			$limit
		");
		
		while($row = $result->fetch_row()){
			$rids[] = $row[0];
		}
		
		return $rids;
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
			FROM nuratings
			WHERE id = {$this->data['id']}
			LIMIT 1
		");
		
		return $result->num_rows;
	}
	
	/**
	* Insert information into the database
	*
	* @param Array $args key/value pairs of data to be inserted
	*	table: String
	*	data: Array
	* @return Boolean
	*/
	public function insert($args = null) {
		global $config;
		$count = 0;
		$len = count($args['data']);
		
		foreach($args['data'] as $key => $val){
			$count++;
			
			if(!get_magic_quotes_gpc()){
				if(is_string($val))
					$val = $config['database']->real_escape_string($val);
			}
			
			if($count != $len){
				$cols .= $key.', ';
				if(is_int($val) || is_float($val)) $vals .= $val.', ';
				elseif(is_string($val)) $vals .= "'".$val."', ";
			}else{
				$cols .= $key;
				if(is_int($val) || is_float($val)) $vals .= $val;
				elseif(is_string($val)) $vals .= "'".$val."'";
			}
		}
		
		$result = $config['database']->query("
			INSERT INTO {$args['table']} ($cols, date_submitted)
			VALUES ($vals, NOW())
		");
		
		$this->data['last_id'] = $config['database']->insert_id;
		
		return $result;
	}
	
	/**
	* Create a new rating and insert into the database
	*
	* @param Array $args key/value pairs of data to be inserted
	* @return Boolean
	*/
	public function create($args = null) {
		global $config;
		
		if(!get_magic_quotes_gpc()){
			foreach($args as $key => $val){
				if(is_string($val))
					$args[$key] = $config['database']->real_escape_string($val);
			}
		}
		
		$count = 0;
		$len = count($args);
		
		foreach($args as $key => $val){
			$count++;
			if($count != $len){
				$cols .= $key.', ';
				if(is_int($val) || is_float($val)) $vals .= $val.', ';
				elseif(is_string($val)) $vals .= "'".$val."', ";
			}else{
				$cols .= $key;
				if(is_int($val) || is_float($val)) $vals .= $val;
				elseif(is_string($val)) $vals .= "'".$val."'";
			}
		}
		
		$result = $config['database']->query("
			INSERT INTO nuratings ($cols, date_submitted)
			VALUES ($vals, NOW())
		");
		
		$this->data['last_id'] = $config['database']->insert_id;
		
		return $result;
	}
	
}
?>