<?php
function generateRandomString($length = 10) {    
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
	$sectoken = generateRandomString();
$date = date('U');
echo $date;
echo $sectoken;
echo $email;

?>