<?php

if (isset($_GET['email'])){

	require "scripts/connect_to_mysql.php";
	
			function clean($elem) { 
			    if(!is_array($elem)){
					$elem = htmlentities($elem,ENT_QUOTES,"UTF-8"); 
				} else {
					foreach ($elem as $key => $value) {
						$elem[$key] = mysql_real_escape_string($value);
					}
				return $elem;
				}
			}
			
			$_CLEANED = clean($_GET);
	
	$email = $_CLEANED['email'];
	
	$user_check = mysql_query("SELECT * FROM users WHERE email='$email'") or die(mysql_error());
	$user_count = mysql_num_rows($user_check);
	if ($user_count > 0) {
	
	// Generate Random Reset Token
	function generateRandomString($length = 20) {    
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
	$sectoken = generateRandomString();
	$secstamp = date("U");		// This token will last 15 minutes
	
	// Store Temporary Token
	$store_token = mysql_query("UPDATE users SET sectoken='$sectoken', secstamp='$secstamp' WHERE email='$email'") or die(mysql_error());
	
	
	// Send the Reset Link to User's Email
	
				$to = "$email";
				$subject = "Reset Your Countliss Password!";
				$from = "no-reply@pieta.x10.mx";
				$headers = "From:" . $from . "\r\n";
				$headers .= "Reply-To: ". $from . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$body = "<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,500,600' rel='stylesheet' type='text/css'>
	</head>
	<style>
.drop-shadow:before,
.drop-shadow:after {
    content:'';
    position:absolute; 
    z-index:-2;
}

 
.lifted:before,
.lifted:after { 
    bottom:15px;
    left:10px;
    width:50%;
    height:20%;
    max-width:300px;
    -webkit-box-shadow:0 15px 10px rgba(0, 0, 0, 0.7);   
    -moz-box-shadow:0 15px 10px rgba(0, 0, 0, 0.7);
    box-shadow:0 15px 10px rgba(0, 0, 0, 0.7);
    -webkit-transform:rotate(-3deg);    
    -moz-transform:rotate(-3deg);   
    -ms-transform:rotate(-3deg);   
    -o-transform:rotate(-3deg);
    transform:rotate(-3deg);
}
 
.lifted:after {
    right:10px; 
    left:auto;
    -webkit-transform:rotate(3deg);   
    -moz-transform:rotate(3deg);  
    -ms-transform:rotate(3deg);  
    -o-transform:rotate(3deg);
    transform:rotate(3deg);
}
		/*============================ LIFTED CORNER DROP SHADOW ===========================*/

	</style>
	<body>
	<table class='wrap drop-shadow lifted' cellpadding='0' cellspacing='0' style='width: 500; margin: 10 auto;
	position:relative;
    background:#fff;
    box-shadow: 0px 0px 20px #eee;
    -o-border-radius:4px;
    -webkit-border-radius:4px;
    -moz-border-radius:4px;
    border-radius:4px;'>
		<tr bgcolor='#F5FAD4'><td>
			<a href='http://pieta.x10.mx'><img src='http://pieta.x10.mx/media/logo.png' style='margin: 20px' /></a></td></tr>
		<tr><td style='padding: 20px'>
	<p style='font-family: Source Sans Pro, Arial, sans-serif;'>Hi $firstname,<br><br>
	Click below to reset your password.</p><br>

	<a href='http://pieta.x10.mx/reset?email=kevinkong91@gmail.com&s=$sectoken' style='font-weight: 600; text-decoration:none; padding: 10px 20px;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #b9cc2d), color-stop(1, #8cb82b) );
			background:-moz-linear-gradient( center top, #b9cc2d 5%, #8cb82b 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#b9cc2d, endColorstr=#8cb82b);
			background-color:#b9cc2d;
			border-radius:			4px;
			-webkit-border-radius:	4px;
			-moz-border-radius:		4px;
			-o-border-radius:		4px;
			text-shadow: 1px 1px 0px #689324;
			color: white;
			font-family: Source Sans Pro, Arial, sans-serif;'>Reset Password</a><br><br>

	<p style='font-family: Source Sans Pro, Arial, sans-serif;'>If you are not $firstname or wish to cancel this request, please ignore this email.<br><br>

	Cheers,<br>
	The Countliss Team</p></td></tr>
	</table>
	
		</div>
		</div>
	</div>
	</body>
	</html>";
				
	mail($to,$subject,$body,$headers);
				
	$result = "<font id='success'>Check Your Inbox!</font>";
	
	} else {
		$result = "<font id='error'>That email is not registered!</font>";
	}
	
	echo $result;
}
?>