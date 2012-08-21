<div id="fb-root"></div>
<script type="text/javascript">
/* ----- Initialize Facebook Connect ----- */
window.fbAsyncInit = function(){
	FB.init({
      appId      : '<?php echo $config['fbappid']; ?>', // App ID
      //channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });
    
	//FB.init({appId: '<?php echo $config['fbappid']; ?>', status: true, cookie: true, xfbml: true});

	// Register events
	FB.Event.subscribe('auth.login', function(response){
		login();
	});
	FB.Event.subscribe('auth.logout', function(response){
		logout();
	});
	<?php
	if(empty($_SESSION['user']))
		$autologin = true;
	else
		$autologin = false;
		
	switch($page){
		case 'join':
		case 'login':
		case 'verify':
		case 'verified':
			$autologin = true;
			break;
		default:
			$autologin = false;
			break;
	}
	
	if($autologin){ // User not logged in or is on a login page, check for FB credentials ?>
	FB.getLoginStatus(function(response) {
		if (response.session) {
			login();
		}
	});
	<?php } ?>
};
// Include Facebook JS
/*
(function(){
	var _e = document.createElement('script');
	_e.type = 'text/javascript';
	_e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
	//_e.src = 'https://connect.facebook.net/en_US/all.js';
	_e.async = true;
	document.getElementById('fb-root').appendChild(_e);
}());
*/
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $config['fbappid']; ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function login(){
	document.location.href = '<?php echo $config['baseurl']; ?>/home';
}

function logout(){
	document.location.href = '<?php echo $config['baseurl']; ?>/logout';
}
</script>