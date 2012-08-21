<?php
//=============================================================================
// Class: Notification
// Purpose: Handles the creation and display of front-end notifcations
//=============================================================================
class Notification {
	private $data = array();
	
	/**
	* Constructor
	*
	* @param Array $args optional parameters
	*	type: String
	*	message: String wrapped in <h5>
	*	submessage: String wrapperd in <p>
	*	image: String URL
	*	image_size: Integer
	*	image_title: String
	*	image_mirror: Boolean mirror image on right
	*	points: Integer points earned
	*	close: String ID
	*/
	public function __construct($args = null) {
		$default = array(
			'type' => 'note',
			'message' => null,
			'submessage' => null,
			'image' => null,
			'image_size' => 50,
			'image_title' => null,
			'image_mirror' => false,
			'points' => null,
			'close' => null
		);
		
		if($args)
			$params = array_merge($default, $args);
		else
			$params = $default;
		
		foreach($params as $key => $val){
			$this->data[$key] = $val;
		}
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
	* Display this
	*
	* @return String HTML
	*/
	public function display() {
		switch($this->data['type']){
			case 'note':
				$class = 'note';
				break;
				
			case 'error':
				$class = 'fail';
				break;
				
			case 'success':
				$class = 'success';
				break;
				
			case 'badge':
				$class = 'badgewin';
				break;
		}
	
		$html = '<div class="announcement '.$class.'">';
		
		if($this->data['image'])
			$html .= '<img class="left" src="'.$this->data['image'].'" width="'.$this->data['image_size'].'" height="'.$this->data['image_size'].'" alt="'.$this->data['image_title'].'" title="'.$this->data['image_title'].'" />';
		
		if($this->data['image'] && $this->data['image_mirror'])
			$html .= '<img class="right" src="'.$this->data['image'].'" width="'.$this->data['image_size'].'" height="'.$this->data['image_size'].'" alt="'.$this->data['image_title'].'" title="'.$this->data['image_title'].'" />';
		
		if($this->data['points'])
			$html .= '<div class="points">+'.$this->data['points'].' pts</div>';
		
		if($this->data['close'])
			$html .= '<a id="'.$this->data['close'].'" class="close" href="#">'.$this->data['close'].'</a>';
		
		if($this->data['message'])
			$html .= '<h5>'.$this->data['message'].'</h5>';
		
		if($this->data['submessage'])
			$html .= '<p>'.$this->data['submessage'].'</p>';
		
		$html .= '</div>';
		
		return $html;
	}
	
}
?>