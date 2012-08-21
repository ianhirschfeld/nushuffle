<?php
//=============================================================================
// Class: Department
// Purpose: Stores all relevant data and functions of a department
//=============================================================================
class Department {

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
				FROM nudepartments
				WHERE id = {$this->data['id']}
				LIMIT 1
			");
			$row = $result->fetch_assoc();
			
			foreach($row as $key => $val){
				if(is_string($val)){
					if(!get_magic_quotes_gpc())
						$this->data[$key] = stripslashes($val);
					else
						$this->data[$key] = $val;
				}else{
					$this->data[$key] = $val;
				}
			}
			
			// Set department grade
			$result2 = $config['database']->query("
				SELECT COUNT(*) AS score_count
				FROM nuratings
				WHERE dept_id = {$this->data['id']}
					AND enabled = 1
			");
			$row2 = $result2->fetch_row();
			
			$result3 = $config['database']->query("
				SELECT score
				FROM nuratings
				WHERE dept_id = {$this->data['id']}
					AND enabled = 1
			");
			
			$total = 0;
			while($row3 = $result3->fetch_row()){
				$total += $row3[0];
			}
			
			if($row2[0] > 0)
				$grade = round($total / $row2[0], 2); // Round to 2 decimals
			else
				$grade = $total;
			$this->data['grade'] = $grade;
			
			return $result;
		}else{
			$this->data['id'] = null;
			return false;
		}
	}
	
	/**
	* Load the most rated departments
	*
	* @param Integer number of departments to return
	* @return Array departments
	*/
	public function loadMostRated($num = 10) {
		global $config;
		$depts = array();
		
		if($num > 0)
			$limit = 'LIMIT 0, '.$num;
		else
			$limit = '';
	
		$result = $config['database']->query("
			SELECT COUNT(*) AS dept_count, dept_id 
			FROM nuratings
			WHERE enabled = 1
				AND dept_id != 152
			GROUP BY dept_id 
			ORDER BY dept_count DESC 
			$limit
		"); // Skips id for NU Shuffle.com
		
		while($row = $result->fetch_row()){
			$depts[] = new Department(array('id' => $row[1]));
		}
		
		usort($depts, array($this, 'sortByGrade'));
		
		return $depts;
	}
	
	/**
	* Load all department ids
	*
	* @return Array department ids
	*/
	public function loadAllDeptIds() {
		global $config;
		$dids = array();
		
		$result = $config['database']->query("
			SELECT id
			FROM nudepartments
			ORDER BY name ASC
		");
		
		while($row = $result->fetch_row()){
			$dids[] = $row[0];
		}
		
		return $dids;
	}
	
	/**
	* Check if this exists
	*
	* @return Boolean
	*/
	public function exists() {
		global $config;
		
		$result = $config['database']->query("
			SELECT id
			FROM nudepartments
			WHERE id = {$this->data['id']}
			LIMIT 1
		");
		
		return $result->num_rows;
	}
	
	/**
	* Create a new department and insert into the database
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
			INSERT INTO nudepartments ($cols, new)
			VALUES ($vals, 1)
		");
		
		$this->data['last_id'] = $config['database']->insert_id;
		
		return $result;
	}
	
	/**
	* Display grade converted from number to letter
	*
	* @return String grade
	*
	* Grading
		A+	4.67
		A	4.33
		A-	4.00
		B+	3.67
		B	3.33
		B-	3.00
		C+	2.67
		C	2.33
		C-	2.00
		D+	1.67
		D	1.33
		D-	1.00
		F	< 1.00
		n/a	0
	*/
	public function displayGrade() {
		switch(true){
			case ($this->data['grade'] >= 4.67):
				$grade = 'A+';
				break;
			case ($this->data['grade'] >= 4.33):
				$grade = 'A';
				break;
			case ($this->data['grade'] >= 4.00):
				$grade = 'A-';
				break;
			case ($this->data['grade'] >= 3.67):
				$grade = 'B+';
				break;
			case ($this->data['grade'] >= 3.33):
				$grade = 'B';
				break;
			case ($this->data['grade'] >= 3.00):
				$grade = 'B-';
				break;
			case ($this->data['grade'] >= 2.67):
				$grade = 'C+';
				break;
			case ($this->data['grade'] >= 2.33):
				$grade = 'C';
				break;
			case ($this->data['grade'] >= 2.00):
				$grade = 'C-';
				break;
			case ($this->data['grade'] >= 1.67):
				$grade = 'D+';
				break;
			case ($this->data['grade'] >= 1.33):
				$grade = 'D';
				break;
			case ($this->data['grade'] >= 1.00):
				$grade = 'D-';
				break;
			case ($this->data['grade'] > 0):
				$grade = 'F';
				break;
			default:
				$grade = 'n/a';
				break;
		}
		
		return $grade;
	}
	
	/**
	* Display average rating in half star increments
	*
	* @return String HTML
	*/
	public function displayAvgRating() {
		$html = '';
		$rounded = round(round($this->data['grade']*10)/5)*5;
		$empty = 50 - $rounded;
		
		while($rounded > 0){
			if($rounded == 5){
				$html .= '<div class="star half"></div>';
				$rounded -= 5;
			}else{
				$html .= '<div class="star full"></div>';
				$rounded -= 10;
			}
		}
		
		while($empty > 5){
			$html .= '<div class="star empty"></div>';
			$empty -= 10;
		}
		
		return $html;
	}
	
	/**
	* Sort array of departments by grade
	*
	* @param Integer $a, $b
	* @return Integer indicating positiong of $a to $b
	*/
	function sortByGrade($a, $b) {
		if($a->grade < $b->grade)
			return 1;
		elseif($a->grade > $b->grade)
			return -1;
		else
			return 0;
	}

}
?>