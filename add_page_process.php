<?php

if (isset($_POST['name'])) {

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

	$name = $_CLEANED['name'];
	
	if ($name == "") {		// If name isn't blank
		die("Something went wrong!");
	} else {
		
		// Check if the lissting exists
		$check = mysql_query("SELECT name FROM pages WHERE name='$name'") or die (mysql_error());
		$count = mysql_num_rows($check);
		if ($count > 0) {
			echo "<font id='error'>That page already exists! Try adding another.</font>";
		} else {

			$name = $_CLEANED['name'];
			$site = $_CLEANED['website'];
			$industry = $_CLEANED['industry'];
			
			// Create Listing
			$new_page = mysql_query("INSERT INTO pages (name, industry, site) VALUES ('$name','$industry','$site')") or die (mysql_error());
			$id = mysql_insert_id();
					
			// Make a folder to hold files for user
			$path = "pages/$id/";
			if (!is_dir($path)) { mkdir($path); }
			
			echo "<font id='success'>Done! You should <a href='add_page_photo?name=$name'>add a colorful logo</a>, too!</font>";
		}
	}
}

?>