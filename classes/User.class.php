<?php
//=============================================================================
// Class: User
// Purpose: Stores all relevant data and functions of the current logged in user
//=============================================================================
class User {
	
	const SALT = '0G9@!a39ga987*&^%ga9320vaweDSasFa';
	private $data = array();
	
	/**
	* Constructor
	*
	* @param Array $args optional parameters
	*	id: Integer
	*/
	public function __construct($args = null) {
		$default = array(
			'id' => null,
			'fbid' => null
		);
		
		if($args)
			$params = array_merge($default, $args);
		else
			$params = $default;
		
		foreach($params as $key => $val){
			$this->data[$key] = $val;
		}
		
		if(!empty($this->data['id']) || !empty($this->data['fbid']))
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
	
		if($this->exists()){ // User exists
			if(!empty($this->data['id']))
				$where = 'id = '.$this->data['id'];
			elseif(!empty($this->data['fbid']))
				$where = 'fbid = '.$this->data['fbid'];
				
			$result = $config['database']->query("
				SELECT *
				FROM nuusers
				WHERE $where
				LIMIT 1
			");
			$row = $result->fetch_assoc();
			
			foreach($row as $key => $val){
				if($key == 'options'){
					$this->data['options'] = json_decode($val, true);
				}else if(is_string($val)){
					if(!get_magic_quotes_gpc())
						$this->data[$key] = stripslashes($val);
					else
						$this->data[$key] = $val;
				}else{
					$this->data[$key] = $val;
				}
			}
			
			if(empty($this->data['photo'])){
				global $config;
				
				if($this->data['faculty'])
					$this->data['photo'] = $config['imagesurl'].'/faculty.jpg';
				else
					$this->data['photo'] = $config['imagesurl'].'/anonymous.gif';
				
			}
			
			$this->data['level'] = $this->loadLevel();
			$this->data['levelProgress'] = $this->loadLevelProgress();
			$this->data['rank'] = $this->loadRank();
			$this->data['achievements'] = $this->loadAchievements();
			$this->data['post_counts'] = $this->loadPostCounts();
			
			return $result;
		}else{ // User does not exists
			if($this->data['fbid']){ // New user from facebook
				global $fbme;
				
				$y = substr($fbme['birthday'],6);
				$m = substr($fbme['birthday'],0,2);
				$d = substr($fbme['birthday'],3,2);
				$bday = $y.'-'.$m.'-'.$d;
				
				$create = $this->create(array(
					'first_name' => $fbme['first_name'],
					'last_name' => $fbme['last_name'],
					'email' => $fbme['email'],
					'birthday' => $bday,
					'gender' => $fbme['gender']
				));
				
				return $create;
			}else{ // Invalid user id
				$this->data['id'] = null;
				return false;
			}
		}
	}
	
	/**
	* User's level calculated from points
	*
	* Level, Points
		1	0
		2	300
		3	600
		4	1000
		5	1500
		6	2000
		7	2500
		8	3000
		9	3500
		10	4000
		11	4500
		12	5000
		13	6000
		14	7000
	* @return Integer
	*/
	public function loadLevel() {
		switch(true){
			case ($this->data['level_pts'] >= 5000):
				$level = floor($this->data['level_pts'] / 1000) + 7;
				break;
			case ($this->data['level_pts'] >= 1000):
				$level = floor($this->data['level_pts'] / 500) + 2;
				break;
			case ($this->data['level_pts'] >= 600):
				$level = 3;
				break;
			case ($this->data['level_pts'] >= 300):
				$level = 2;
				break;
			default:
				$level = 1;
				break;
		}
		
		return $level;
	}
	
	/**
	* User's progress towards the next level
	*
	* @return Integer
	*/
	public function loadLevelProgress() {
		switch(true){
			case ($this->data['level_pts'] >= 5000):
				$progress = $this->data['level_pts'] % 1000;
				$progress = floor($progress / 10);
				break;
			case ($this->data['level_pts'] >= 1000):
				$progress = $this->data['level_pts'] % 500;
				$progress = floor($progress / 5);
				break;
			case ($this->data['level_pts'] >= 600):
				$progress = $this->data['level_pts'] % 600;
				$progress = floor($progress / 4);
				break;
			case ($this->data['level_pts'] >= 300):
				$progress = $this->data['level_pts'] % 300;
				$progress = floor($progress / 3);
				break;
			default:
				$progress = floor($this->data['level_pts'] / 3);
				break;
		}
		
		return $progress;
	}
	
	/**
	* User's rank based on points vs total
	*
	* @return Integer
	*/
	public function loadRank() {
		global $config;
		$rank = 0;
		
		$result = $config['database']->query("
			SELECT @row := @row + 1 AS row, id
			FROM nuusers, (SELECT @row:=0) r
			ORDER BY level_pts DESC
		");
		
		while($row = $result->fetch_assoc()){
			if($row['id'] == $this->data['id']){
				$rank = $row['row'];
				break;
			}
		}
		
		return $rank;
	}
	
	/**
	* User's achievements
	*
	* @return Array achievements
	*/
	public function loadAchievements() {
		global $config;
		$achievements = array();
		
		$result = $config['database']->query("
			SELECT achievement_id
			FROM nuuserachievements
			WHERE user_id = {$this->data['id']}
			ORDER BY date_achieved DESC
		");
		
		while($row = $result->fetch_row()){
			$achievements[] = new Achievement(array('id' => $row[0]));
		}
		
		return $achievements;
	}
	
	/**
	* User's post counts by rating
	*
	* @return Array of post counts by rating
	*/
	public function loadPostCounts() {
		global $config;
		$post_counts = array(
			'total' => 0,
			'five_stars' => 0,
			'four_stars' => 0,
			'three_stars' => 0,
			'two_stars' => 0,
			'one_star' => 0,
			'phone_call' => 0,
			'forms' => 0,
			'website' => 0,
			'in_person' => 0,
			'other' => 0
		);
	
		$result = $config['database']->query("
			SELECT id
			FROM nuratings
			WHERE user_id = {$this->data['id']}
				AND score = 5
				AND enabled = 1
			GROUP BY shuffle_id
		");
		$post_counts['five_stars'] = $result->num_rows;
		$post_counts['total'] += $result->num_rows;
		
		$result = $config['database']->query("
			SELECT id
			FROM nuratings
			WHERE user_id = {$this->data['id']}
				AND score = 4
				AND enabled = 1
			GROUP BY shuffle_id
		");
		$post_counts['four_stars'] = $result->num_rows;
		$post_counts['total'] += $result->num_rows;
		
		$result = $config['database']->query("
			SELECT id
			FROM nuratings
			WHERE user_id = {$this->data['id']}
				AND score = 3
				AND enabled = 1
			GROUP BY shuffle_id
		");
		$post_counts['three_stars'] = $result->num_rows;
		$post_counts['total'] += $result->num_rows;
		
		$result = $config['database']->query("
			SELECT id
			FROM nuratings
			WHERE user_id = {$this->data['id']}
				AND score = 2
				AND enabled = 1
			GROUP BY shuffle_id
		");
		$post_counts['two_stars'] = $result->num_rows;
		$post_counts['total'] += $result->num_rows;
		
		$result = $config['database']->query("
			SELECT id
			FROM nuratings
			WHERE user_id = {$this->data['id']}
				AND score = 1
				AND enabled = 1
			GROUP BY shuffle_id
		");
		$post_counts['one_star'] = $result->num_rows;
		$post_counts['total'] += $result->num_rows;
		
		$result = $config['database']->query("
			SELECT id
			FROM nuratings
			WHERE user_id = {$this->data['id']}
				AND type = 'Phone Call'
				AND enabled = 1
			GROUP BY shuffle_id
		");
		$post_counts['phone_call'] = $result->num_rows;
		
		$result = $config['database']->query("
			SELECT id
			FROM nuratings
			WHERE user_id = {$this->data['id']}
				AND type = 'Forms'
				AND enabled = 1
			GROUP BY shuffle_id
		");
		$post_counts['forms'] = $result->num_rows;
		
		$result = $config['database']->query("
			SELECT id
			FROM nuratings
			WHERE user_id = {$this->data['id']}
				AND type = 'Website'
				AND enabled = 1
			GROUP BY shuffle_id
		");
		$post_counts['website'] = $result->num_rows;
		
		$result = $config['database']->query("
			SELECT id
			FROM nuratings
			WHERE user_id = {$this->data['id']}
				AND type = 'In Person'
				AND enabled = 1
			GROUP BY shuffle_id
		");
		$post_counts['in_person'] = $result->num_rows;
		
		$result = $config['database']->query("
			SELECT id
			FROM nuratings
			WHERE user_id = {$this->data['id']}
				AND type = 'Other'
				AND enabled = 1
			GROUP BY shuffle_id
		");
		$post_counts['other'] = $result->num_rows;
		
		return $post_counts;
	}
	
	/**
	* Check if this exists
	*
	* @return Boolean
	*/
	public function exists() {
		global $config;
		
		if(!empty($this->data['id']))
			$where = 'id = '.$this->data['id'];
		elseif(!empty($this->data['fbid']))
			$where = 'fbid = '.$this->data['fbid'];
	
		$result = $config['database']->query("
			SELECT id
			FROM nuusers
			WHERE $where
			LIMIT 1
		");
		
		return $result->num_rows;
	}
	
	/**
	* Login this user
	*
	* @param Boolean $isFb
	* @return Boolean
	*/
	public function login($isFb = false) {
		global $config;
		
		$update = $config['database']->query("
			UPDATE nuusers
			SET last_login = NOW()
			WHERE id = {$this->data['id']}
			LIMIT 1
		");
		
		if($isFb && $update && $this->data['fbid']){ // Has facebook id
			global $config;
			$temp_fb = get_data_url("https://api.facebook.com/method/fql.query?query=SELECT%20publish_stream%20FROM%20permissions%20WHERE%20uid%3D{$this->data['fbid']}&access_token={$config['fbappid']}|{$config['fbsecret']}");
			$xmlp = xml_parser_create();
			xml_parse_into_struct($xmlp, $temp_fb, $temp_vals);
			xml_parser_free($xmlp);
			foreach($temp_vals as $val){
				if($val['tag'] == 'PUBLISH_STREAM')
					$this->data['fb_can_publish'] = $val['value'];
			}
		}
		
		return $update;
	}
	
	/**
	* Check credentials of this
	*
	* @return Integer id of User, 0 if invalid
	*/
	public function check() {
		global $config;
		
		$email = $this->data['email'];
		$password = hash('whirlpool',$this->SALT.$this->data['password']);
		
		$result = $config['database']->query("
			SELECT id
			FROM nuusers
			WHERE email = '$email'
				AND password = '$password'
			LIMIT 1
		");
		
		if($result->num_rows){
			$row = $result->fetch_row();
			return $row[0];
		}else{
			return 0;
		}
	}
	
	/**
	* Check if this already has achievement
	*
	* @param Integer $aid Achievment id
	* @return Boolean
	*/
	public function hasAchievement($aid) {
		global $config;
		
		$result = $config['database']->query("
			SELECT id
			FROM nuuserachievements
			WHERE user_id = {$this->data['id']}
				AND achievement_id = $aid
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
		
		if($args['table'] == 'nuuserprizes') $date_col = 'date_redeemed';
		elseif($args['table'] == 'nuuserachievements') $date_col = 'date_achieved';
		
		$result = $config['database']->query("
			INSERT INTO {$args['table']} (user_id, $cols, $date_col)
			VALUES ({$this->data['id']}, $vals, NOW())
		");
		
		$this->data['last_id'] = $config['database']->insert_id;
		
		if($args['table'] == 'nuuserachievements'){
			$a = new Achievement(array('id' => $args['data']['achievement_id']));
			array_unshift($this->data['achievements'], $a);
		}
		
		return $result;
	}
	
	/**
	* Update information in the database
	*
	* @param Array $args key/value pairs of data to be updated
	* @return Boolean
	*/
	public function update($args = null) {
		global $config;
		
		$updates = array();
		foreach($args as $key => $val){
			if($this->data[$key] != $val){
				if(!get_magic_quotes_gpc()){
					if(is_string($val))
						$val = $config['database']->real_escape_string($val);
				}
				
				$updates[$key] = $val;
			}
		}
		
		$count = 0;
		$len = count($updates);
		foreach($updates as $key => $val){
			$count++;
			if($key == 'options')
				$val = json_encode($val);
		
			if($count != $len){
				if(is_int($val) || is_float($val)) $values .= $key.' = '.$val.', ';
				elseif(is_string($val)) $values .= $key." = '".$val."', ";
			}else{
				if(is_int($val) || is_float($val)) $values .= $key.' = '.$val;
				elseif(is_string($val)) $values .= $key." = '".$val."'";
			}
		}
		
		if(!empty($updates)){
			$result = $config['database']->query("
				UPDATE nuusers
				SET $values
				WHERE id = {$this->data['id']}
				LIMIT 1
			");
			
			if($result){
				foreach($updates as $key => $val){
					$this->data[$key] = $val;
					if($key == 'level_pts'){
						$this->data['level'] = $this->loadLevel();
						$this->data['levelProgress'] = $this->loadLevelProgress();
						$this->data['rank'] = $this->loadRank();
					}
				}
			}
			
			return $result;
		}else{
			return true;
		}
	}
	
	/**
	* Create a new user and insert into the database
	*
	* @param Array $args key/value pairs of data to be inserted
	* @return Boolean
	*/
	public function create($args = null, $verify = false) {
		global $config;
		
		$options = array(
			'twitter' => null,
			'anonymous' => 0,
			'social_post_default' => 1,
			'social_badge_default' => 1
		);
		$args['options'] = json_encode($options);
		
		if(!get_magic_quotes_gpc()){
			foreach($args as $key => $val){
				if($key != 'password' && $key != 'options'){
					if(is_string($val))
						$args[$key] = $config['database']->real_escape_string($val);
				}
			}
		}
		
		$args['password'] = hash('whirlpool',$this->SALT.$args['password']);
		
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
			INSERT INTO nuusers ($cols)
			VALUES ($vals)
		");
		
		$this->data['last_id'] = $config['database']->insert_id;
		
		if($result && $verify)
			$this->sendVerifyEmail($config['database']->insert_id, $args['email'], $args['password']);
		
		return $result;
	}
	
	/**
	* Send verification email and insert temporary verify hash into database
	*
	* @param Integer $id
	* @param String $email, $password
	* @return String hash
	*/
	public function sendVerifyEmail($id, $email, $password) {
		global $config;
		$hash = md5($this->SALT.$email.$password);
		
		$result = $config['database']->query("
			INSERT INTO nuuserverify (user_id, hash, date_created)
			VALUE ($id, '$hash', NOW())
		");
	
		$to = $email;
		$from = 'NU Shuffle <join@northeasternshuffle.com>';
		$subject = 'NU Shuffle Verification';
		
		$body = "Thank you for joining NU Shuffle!\n\n";
		$body .= "Verify your account by clicking the following link: {$config['baseurl']}/verify/$hash\n\n";
		$body .= "Or manually verify by going to {$config['baseurl']}/verify and entering: $hash\n\n";
		$body .= "If you experience any issues email us at support@northeasternshuffle.com.\n\n";
		$body .= "Sincerely,\n";
		$body .= "The NU Shuffle Team";
		
		$headers = "From: $from\r\n";
		$headers .= "Reply-To: $from\r\n";
		$headers .= "X-Mailer: PHP/" . phpversion();
		
		$sent = mail($to, $subject, $body, $headers);
		
		if($result && $sent)
			return true;
		else
			return false;
	}
	
}
?>