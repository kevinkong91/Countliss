<?php

if (isset($_GET['email'])) {
	$fname = $_GET['fname'];
	$lname = $_GET['lname'];
	$email = $_GET['email'];
	$password = $_GET['password'];
	
	if (($fname == "")||($lname == "")||($email == "")||($password == "")) {
	} else {
		
		require "scripts/connect_to_mysql.php";
		
		// Check if the user exists
		$user_check = mysql_query("SELECT email FROM users WHERE email='$email'") or die (mysql_error());
		$user_count = mysql_num_rows($user_check);
		if ($user_count > 0) {
			echo "<font id='error'>Email taken! Sign in or try another.</font>";
		} else {
		
			// Passwords should never be longer than 72 characters to prevent DoS attacks
			if (strlen($password) > 72) { die("Password must be 72 characters or less"); }


			/*============== Sanitizing Inputs for SQL Attack/Interception ===============*/
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

			$fname = $_CLEANED['fname'];
			$lname = $_CLEANED['lname'];
			$email = $_CLEANED['email'];
			$password = $_CLEANED['password'];
			
			/*============== Sanitizing Email Input for Spam Attack/Interception ===============*/
			function spamcheck($field) {
				//filter_var() sanitizes the e-mail
				//address using FILTER_SANITIZE_EMAIL
				$field=filter_var($field, FILTER_SANITIZE_EMAIL);

				//filter_var() validates the e-mail
				//address using FILTER_VALIDATE_EMAIL
				if(filter_var($field, FILTER_VALIDATE_EMAIL)){
					return TRUE;
				} else {
					return FALSE;
				}
			}
			
			//check if the email address is invalid
			$mailcheck = spamcheck($_REQUEST['email']);
			if ($mailcheck==FALSE){
				echo "<font id='error'>That is not a valid email address!</font>";
			} else { // ALL GOOD! now send email
				
				require "PasswordHash.php";		// Secure PHPass hashing
				$hasher = new PasswordHash(8, false);
				$hash = $hasher->HashPassword($password);   // The $hash variable will contain the hash of the password

				if (strlen($hash) >= 20) {
				
					// Generating Random Token for Verification
					function generateRandomString($length = 20) {    
						return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
					}
					$verify_token = generateRandomString();
					
					// Create User
					$new_user = mysql_query("INSERT INTO users (firstname, lastname, email, password, ip, lastactivity, signedup, sectoken) VALUES ('$fname','$lname','$email','$hash','$user_ip',now(),now(),'$verify_token')") or die (mysql_error());
					$uid = mysql_insert_id();
					
					// Make a folder to hold files for user
					if (!is_dir("users/$user_id")) { mkdir("users/$user_id"); }
				
					// Modify Session Variables for User
					$_SESSION['uid'] = $uid;
       				$_SESSION['loggedin'] = true;
       				$_SESSION['lastactivity'] = date("Y-m-d H:i:s");
       				//$_SESSION['lifetime'] = now();
       				
       				// Get info from visitor session info
       				$user_ip = $_SESSION['ip'];
       				$sessid = $_SESSION['sessid'];
       				
       				// Create Cookies
       				$expire=time()+60*60*24*30;  // 30 days
       				setcookie("uid", "$uid", $expire);
					setcookie("ip", "$user_ip", $expire);
					setcookie("email", "$email", $expire);
					setcookie("firstname", "$firstname", $expire);
					setcookie("lastname", "$lastname", $expire);
					
					// Send Email Verification
					$to = "$email";
					$subject = "Verify Your Countliss Email!";
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

	<a href='http://pieta.x10.mx/verify?email=kevinkong91@gmail.com&s=$verify_token' style='font-weight: 600; text-decoration:none; padding: 10px 20px;
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
					echo "<font id='success'>Welcome!</font>";
				} else {
					echo "<font id='error'>Something's wrong. Try again!</font>";
				}
			}
		}
	}
}

?>