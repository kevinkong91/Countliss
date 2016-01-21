<?php

if (isset($_GET['email'])) {
	
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
		$password= $_CLEANED['password'];
		$remember = $_GET['remember'];

	if (($email == "")||($password == "")) {
	} else {
		
		$user_check = mysql_query("SELECT * FROM users WHERE email='$email' LIMIT 1") or die(mysql_error());
		$user_count = mysql_num_rows($user_check);
		if ($user_count > 0) {
			while ($u = mysql_fetch_array($user_check)) {
				$stored_hash = $u['password'];
				$uid = $u['id'];
				$firstname = $u['firstname'];
				$lastname = $u['lastname'];
			}
				require("PasswordHash.php");
		
				$hasher = new PasswordHash(8, false);
		
				// Passwords should never be longer than 72 characters to prevent DoS attacks
				if (strlen($password) > 72) { die("Password must be 72 characters or less"); }

				// Check that the password is correct, returns a boolean
				$check = $hasher->CheckPassword($password, $stored_hash);

				if ($check) {
					
					session_start();
					$sessid = session_id();	
					
					// passwords matched!
    	    		$_SESSION['uid'] = $uid;
       				$_SESSION['firstname'] = $firstname;
       				$_SESSION['lastname'] = $lastname;
       				$_SESSION['email'] = $email;
					
					mysql_query("UPDATE visitors SET uid='$uid', lastactivity=now() WHERE id='$sessid'") or die (mysql_error());
					mysql_query("UPDATE users SET lastactivity=now() WHERE email='$email'") or die (mysql_error());
				
					if ($remember == "yes") {
						setcookie("uid", $uid, time()+90*24*60*60, "/"); // 90 days; 24 hours; 60 mins; 60secs
						setcookie("firstname", $firstname, time()+90*24*60*60, "/");
						setcookie("lastname", $lastname, time()+90*24*60*60, "/");
					}
					
					echo "<font id='success'>Welcome!</font>";
				} else {
					echo "<font id='error'>Invalid email/password combination!</font>"; // passwords didn't match, error
				}
		
			
		} else {
			echo "<font id='error'>Invalid email/password combination!</font>";
		}
	}
}

?>