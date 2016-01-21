<?php

if (isset($_POST['msg'])) {

	require "scripts/connect_to_mysql.php";
	
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
			
			$_CLEANED = clean($_POST);

	$msg = $_CLEANED['msg'];
	$email = $_CLEANED['replyto'];
	
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
			
	// check if the email address is invalid
	$mailcheck = spamcheck($email);
	if ($mailcheck==FALSE) {
		echo "<font id='error'>That is not a valid email address!</font>";
	} else {
	
	if ($msg == "") {		// If name isn't blank
		die("Something went wrong!");
	} else {
		
		// Check if the msg has been sent already
		$check = mysql_query("SELECT * FROM feedback WHERE msg='$msg'") or die (mysql_error());
		$count = mysql_num_rows($check);
		if ($count > 0) {
			echo "<font id='error'>You've already sent this feedback!</font>";
		} else {

			// Create Listing
			$new_feedback = mysql_query("INSERT INTO feedback (msg, email, datestamp) VALUES ('$msg','$email',now())") or die (mysql_error());
			$id = mysql_insert_id();
			
			// Send email-copy to support address
			//
			
			echo "<font id='success'>Hi back! Thank you for your input. Write some more or continue <a href='browse'>browsing</a>!</font>";
		}
	}
	
	}
}

?>