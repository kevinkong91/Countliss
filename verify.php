<?php

require_once "scripts/checkuser.php";
require("scripts/connect_to_mysql.php");

/*=========== CHECK IF USER CAME BACK WITH RIGHT CREDENTIALS & ON TIME ==========*/
if (isset($_GET['email']) && isset($_GET['s'])) {

$email = $_GET['email'];
$sectoken = $_GET['s'];

$user_check = mysql_query("SELECT * FROM users WHERE email='$email' AND sectoken='$sectoken'") or die(mysql_error());
$user_count = mysql_num_rows($user_check);
if ($user_count > 0) {
	// correct email and token!
	
		// Assign Another Random Token -- thereby retiring the one that was just used to verify
		function generateRandomString($length = 20) {    
			return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		}
		$newtoken = generateRandomString();
		$newstamp = date("U") - 900;		// Immediately expiring this "placeholder" token for security purposes
	
		// Store Placeholder Token
		$store_token = mysql_query("UPDATE users SET sectoken='$newtoken', secstamp='$newstamp', verified='1' WHERE email='$email'") or die(mysql_error());
		
		$result = "<p><font id='success'>Your email has been successfully verified!</font><br>Now start using <a href='.'>Countliss!</a></p>";
} else {
	$result = "<p><font id='error'>Error! Try <a href='signup?email=$email'>registering</a> again.</font></p>";
}

}

/*=========== RESET FORM HANDLING ==========*/
if (isset($_POST['email']) && isset($_POST['resetpw'])){
	
	// variable sanitizing
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
			
			$_CLEANED = clean($_POST);
	
		$email = $_CLEANED['email'];
		$resetpw = $_CLEANED['resetpw'];
	
	// Passwords should never be longer than 72 characters to prevent DoS attacks
	if (strlen($resetpw) > 72) { die("Password must be 72 characters or less"); }
	
	require "PasswordHash.php";
	$hasher = new PasswordHash(8, false);

	// The $hash variable will contain the hash of the password
	$hash = $hasher->HashPassword($resetpw);
	
	$new_pw = mysql_query("UPDATE users SET password='$hash' WHERE email='$email'") or die (mysql_error());
	

	header('location: reset?success');
}

if (isset($_GET['success'])) {
	$result = "<p><font id='success'>Your password was successfully reset!</font></p>";
}
 ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<title>Reset Password</title>
<?php include_once "heading.php" ?>
<style>
.highlight {background: #F4FCDE; border: solid 2px #5D751D; }
</style>
</head>
<body>
<div class='wrap'>

<?php include_once "header.php" ?>

<div class='content'>

		<div class='left white'>
		<img src='http://placehold.it/440x600'>
		</div>
	
		<div class='right login'>
		<p><b class='heading'>Verify Email</b></p>
		
		<?php echo $result ?>
		
		</div>

		</div>
		<script>
	$(function(){
		$('#submit').click(function(){
			$('#loading').hide();
			$('#status').empty();
			var email = $('input#email');
			var password = $('input#password');
			
			//Simple validation to make sure user entered something
        	//If error found, add hightlight class to the text field
			if (email.val() == ""){
				$('input#email').addClass('highlight');
			} else { email.removeClass('highlight'); }
			
			if (password.val() == ""){
				password.addClass('highlight');
			} else { password.removeClass('highlight'); }
			
			if (email.val() == "" || password.val() == "") {
				$('span#status').html("<font id='error'>All fields are required!</font>").fadeIn(400).delay(1000).fadeOut(1000);
			} else {
			var formdata = $('form#login').serialize();
			$('#loading').show();
			$.ajax({
				url: 'login_process',
				type: 'GET',
				data: formdata,
				cache: false,
				success: function(response){
					$('#loading').hide();
					$('#status').html(response);
					$('#status').fadeIn(400).delay(1000).fadeOut(400);
				}
			});
			}
			return false;
		});
	});
	</script>

</div>

</div>
	
<?php include_once "footer.php" ?>

</body>
</html>