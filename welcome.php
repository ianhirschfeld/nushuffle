<?php
session_start();
$_SESSION['new_user'] = true;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
<meta name="copyright" content="Copyright 2011 Bionic Hippo, All Rights Reserved." /> 
<meta name="description" content="" /> 
<meta name="keywords" content="" /> 
<link href="favicon.ico" rel="shortcut icon" /> 
<link href="http://css.shufl.es/reset.css" rel="stylesheet" />
<style type="text/css">
body{
	background:#868686;
	text-align:center;
	font-family:Helvetica,Arial,sans-serif;
	font-weight:bold;
	color:#505050;
	font-size:13pt;
	}
a{color:#505050;}
a:hover, a:active{color:#000;}
.globalWrapper{
	width:600px;
	padding:40px;
	padding-bottom:60px;
	margin:0 auto;
	background:#fff url('images/bg.jpg') repeat-x;
	margin-top:30px;
	-moz-box-shadow: 0px 0px 15px #333;
	-webkit-box-shadow: 0px 0px 15px #333;
	box-shadow: 0px 0px 15px #333;
	text-align:left;
	}
h1{
	color:#00aceb;
	font-size:26pt;
	}
h2{
	font-size:18pt;
	margin:30px 0;
	}
img{
	margin:30px 0 60px 100px;
	width:400px;
	height:356px;
	}
.go{
	color:#fff;
	background:#aaa;
	margin-left:190px;
	padding:20px 40px;
	-webkit-border-radius: 7px;
	-moz-border-radius: 7px;
	border-radius: 7px;
	text-decoration:none;
	}
	.go:hover{background:#00aceb;color:#fff;}
.footer{
	margin-top:20px;
	font-size:10pt;
	font-weight:normal;
	}
</style>
<title>Welcome to Northeastern Shuffle</title> 
</head> 
<body>
<div class="globalWrapper">
	<div class="welcome">
		<h1>Welcome to Northeastern Shuffle!</h1>
		<h2>We created a fun and effective way to help resolve your problems with Northeastern and their affiliates.</h2>
		<p>
		This website is about <u>open communication</u>. Our purpose is to collect constructive feedback to make NU better.
		<br /><br />
		If students and faculty can work together, we can kill the shuffle.
		<br /><br /><br /><br />
		Have fun!
		</p>
		<img src="images/welcome.jpg" alt=""><br />
		<a class="go" href="/home">Let's get started!</a>
	</div><!--welcome-->
</div><!--globalWrapper-->
<div class="footer">
	&copy;2011 <a href="http://www.bionichippo.com" target="_blank">Bionic Hippo</a>.
</div><!--footer-->


</body>
</html>