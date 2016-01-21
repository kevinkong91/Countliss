<?php

require "connect_to_mysql.php";
session_start();

					// Get User's Real IP Address
					function getRealIpAddr() {
						if (!empty($_SERVER['HTTP_CLIENT_IP'])){  //check ip from share internet
							$ip=$_SERVER['HTTP_CLIENT_IP'];
						} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
							$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
						} else {
							$ip=$_SERVER['REMOTE_ADDR'];
						} 
						return $ip;
					}
					$user_ip = getRealIpAddr();

/*========= if the user is recognized, repeat visitor =========*/
if (isset($_COOKIE['uid'])) {
	
	$uid = $_COOKIE['uid'];
	
					// Get User's Real IP Address
					function getRealIpAddr() {
						if (!empty($_SERVER['HTTP_CLIENT_IP'])){  //check ip from share internet
							$ip=$_SERVER['HTTP_CLIENT_IP'];
						} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
							$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
						} else {
							$ip=$_SERVER['REMOTE_ADDR'];
						} 
						return $ip;
					}
					$user_ip = getRealIpAddr();
	
	// Populate session with relevant visitor info
	$_SESSION['uid'] = $uid;
	$_SESSION['ip'] = $user_ip;
	$_SESSION['loggedin'] = true;
	$_SESSION['lastactivity'] = date("Y-m-d H:i:s");
	$_SESSION['sessid'] = session_id();
	
	// Obtain personal user info from db
	$user_sql = mysql_query("SELECT * FROM users WHERE id='$uid' LIMIT 1") or die (mysql_error());
	$count = mysql_num_rows($user_sql);
	if ($count > 0) {
		while ($u = mysql_fetch_array($user_sql)) {
			$firstname = $u['firstname'];
			$lastname = $u['lastname'];
			$email = $u['email'];
		}
		
		$_SESSION['firstname'] = $firstname;
		$_SESSION['lastname'] = $lastname;
		
		if ($firstname=="" && $lastname=="") {
			$account = "Guest&nbsp;&nbsp;&nbsp;";
		} else {
			$account = "$firstname $lastname&nbsp;&nbsp;&nbsp;";
		}
		
		// Profile photo
		$profpic = "users/$uid/$profpic.jpg";
		$defaultpic = "users/default.png";
		if (file_exists($profpic)) {
			$thumbnail = "<img src='$profpic' class='medium account' />";
		} else {
			$thumbnail = "<img src='$defaultpic' class='medium account' />";
		}
		
		$thumb = "<a href='profile' id='account_thumb'>$account&nbsp;&nbsp;&nbsp;$thumbnail</a>";
	}

} else {   /*========= visitor is not recognized =========*/

/*========= and is completely new (first-time visit) =========*/
if (!isset($_SESSION['sessid'])) {
	
	// set session info
	$_SESSION['sessid'] = session_id();
    $_SESSION['ip'] = $user_ip;
    $_SESSION['loggedin'] = false;
    $_SESSION['lastactivity'] = date("Y-m-d H:i:s");
    //$_SESSION['lifetime'] = now();
    
    // activity count
    if(isset($_SESSION['views'])) {
		$_SESSION['views']=$_SESSION['views']+1;
		//echo "Views=". $_SESSION['views'];
	} else {
		$_SESSION['views']=1;
	}
	
	$view_count = $_SESSION['views'];
	
	// update visitor info in db
	$sessid = $_SESSION['sessid'];
	$visitor_sql = mysql_query("SELECT * FROM visitors WHERE id='$sessid' LIMIT 1") or die (mysql_error());
	$v_count = mysql_num_rows($visitor_sql);
	if ($v_count > 0) {		// if the visitor is in the database
		while ($v = mysql_fetch_array($visitor_sql)) {
			$visits = $v['count'];
		}
		
		if ($view_count != $visits) {		//	update his view count
			$update = mysql_query("UPDATE visitors SET count='$view_count', ip='$user_ip', status='ON', lastactivity=now() WHERE id='$sessid'") or die (mysql_error());
		}
	} else {		// enter the new visitor into db
		$new_visitor = mysql_query("INSERT INTO visitors (id,count,ip,status,lastactivity) VALUES ('$sessid','$view_count','$user_ip','ON',now())") or die (mysql_error());
	}
	
	$thumb = "<a href='#' id='account_thumb'>Guest&nbsp;&nbsp;&nbsp;<img src='/users/default.png' class='medium account' /></a>
	<div id='account' class='hidden'>
	<div id='top'>
	<form method='post' action='login_process'>
		<input type='email' name='email' placeholder='Email' class='loginform' /><br>
		<input type='password' name='password' placeholder='Password' class='loginform' /><br>
		<input type='checkbox' name='rememberme' id='rememberme' /><label for='rememberme' id='remember'>Remember me</label><input type='submit' value='Log In' class='button right' />
	</form>
	</div>
	<a href='signup'><div id='bottom' class='center'>
	Create an account
	</div></a>
	</div>
	<script>
$(function(){
	$('a#account_thumb').click(function(){
		$('div#account').toggle();
		return false;
	});
});
</script>";
} else {
	/*========= repeat visit, session set =========*/
	// update visitor info in db
	$sessid = $_SESSION['sessid'];
	
	// activity count
    if(isset($_SESSION['views'])) {
		$_SESSION['views']=$_SESSION['views']+1;
		//echo "Views=". $_SESSION['views'];
	} else {
		$_SESSION['views']=1;
	}
	$view_count = $_SESSION['views'];
	
	// get previous view counts, update if necessary
	$visitor_sql = mysql_query("SELECT * FROM visitors WHERE id='$sessid' LIMIT 1") or die (mysql_error());
	$v_count = mysql_num_rows($visitor_sql);
	if ($v_count > 0) {		// if the visitor is in the database
		while ($v = mysql_fetch_array($visitor_sql)) {
			$visits = $v['count'];
		}
		
		if ($view_count != $visits) {		//	update his view count
			$update = mysql_query("UPDATE visitors SET count='$view_count', ip='$user_ip', status='ON', lastactivity=now() WHERE id='$sessid'") or die (mysql_error());
		}
	} else {		// if not in db, enter the new visitor into db
		$new_visitor = mysql_query("INSERT INTO visitors (id,count,ip,status,lastactivity) VALUES ('$sessid','$view_count','$user_ip','ON',now())") or die (mysql_error());
	}
	
	// if logged in, update header and account info
	if ((isset($_SESSION['firstname'])) && (isset($_SESSION['lastname']))) {
		$firstname = $_SESSION['firstname'];
		$lastname = $_SESSION['lastname'];
		$email = $_SESSION['email'];
		
		// Check if their name is empty
		if ($firstname=="" && $lastname=="") {
			$account = "Guest";
		} else {
			$account = "$firstname $lastname";
		}
		
		// Profile photo
		$profpic = "users/$uid/$profpic.jpg";
		$defaultpic = "users/default.png";
		if (file_exists($profpic)) {
			$thumbnail = "<img src='$profpic' class='medium account' />";
		} else {
			$thumbnail = "<img src='$defaultpic' class='medium account' />";
		}
		
		$thumb = "<a href='profile' id='account_thumb'>$account&nbsp;&nbsp;&nbsp;$thumbnail</a>";
	
	} else {
	// not logged in
		$thumb = "<a href='#' id='account_thumb'>Guest <img src='/users/default.png' class='medium account' /></a>
	<div id='account' class='hidden'>
	<div id='top'>
	<form method='post' action='login_process'>
		<input type='email' name='email' placeholder='Email' class='loginform' /><br>
		<input type='password' name='password' placeholder='Password' class='loginform' /><br>
		<input type='checkbox' name='rememberme' id='rememberme' /><label for='rememberme' id='remember'>Remember me</label><input type='submit' value='Log In' class='loginbutton right' />
	</form>
	</div>
	<a href='signup'><div id='bottom' class='center'>
	Create an account
	</div></a>
	</div>
	<script>
$(function(){
	$('a#account_thumb').click(function(){
		$('div#account').toggle();
		return false;
	});
});
</script>";
	}
}

}
?>