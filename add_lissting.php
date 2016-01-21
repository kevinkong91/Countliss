<?php

require_once "scripts/checkuser.php";

if (isset($_GET['page'])) {
	$page = $_GET['page'];
	
	$check = mysql_query("SELECT * FROM pages WHERE name='$page' LIMIT 1") or die (mysql_error());
	$count = mysql_num_rows($check);
	if ($count > 0) {
		while ($l = mysql_fetch_array($check)) {
			$id = $l['id'];
		}
	}
}

// Error Handling
if (isset($_GET['error'])) {
	$error_type = $_GET['error'];
	if ($error_type == "missing") {
		$msg = "The page and name of the lissting are required!";
	} elseif ($error_type == "exists") {
		$msg = "That page already exists! Try adding another.";
	} else {
		$msg = "Something went wrong. Try again!";
	}
	
	$alert = "<font id='error'>$msg</font>";
}

// Success Handling
if (isset($_GET['success'])) {
	$type = $_GET['success'];
	if ($type == "l") {
		$msg = "You've successfully added a lissting!";
	} elseif ($type == "lp") {
		$msg = "You've successfully added a lissting and a new page!";
	} else {
		$msg = "Something went wrong. Try again!";
	}
	
	$alert = "<font id='success'>$msg</font>";
}


// New Lissting Form Handler
if (isset($_POST['page'])) {

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

	$page = $_CLEANED['page'];
	$name = $_CLEANED['name'];
	$product = $_CLEANED['product'];
	$details = $_CLEANED['details'];
	$website = $_CLEANED['website'];
	$price_orig = $_CLEANED['price_orig'];
	$price_red = $_CLEANED['price_red'];
	$discountr = $_CLEANED['discount_rate'];
	$expire_date = $_CLEANED['expires'];
	
	if ($page == "" || $name == "") {
		// If page or name is blank, start again
		header("location: add_lissting?error=missing");
	} else {
		
		// Check if the page exists
		$check = mysql_query("SELECT * FROM pages WHERE name='$page' LIMIT 1") or die (mysql_error());
		$count = mysql_num_rows($check);
		if ($count > 0) {		// If Yes, get page id
			while ($p = mysql_fetch_array($check)){
				$pid = $p['id'];
			}
		} else {			// If not, create page then get page id
			$new_page = mysql_query("INSERT INTO pages (name) VALUES ($name)") or die (mysql_error());
			$pid = mysql_insert_id();
		}
		
		// Check if the lissting exists
		$lcheck = mysql_query("SELECT name FROM lisstings WHERE name='$name' LIMIT 1") or die (mysql_error());
		$lcount = mysql_num_rows($lcheck);
		if ($lcount > 0) {
				
			// If page or name is blank, start again
			header("location: add_lissting?error=exists");
				
		} else {		// If the lissting is new
			
			// Create Listing
			$new_lissting = mysql_query("INSERT INTO lisstings (name, pid, product, details, website, originalprice, reducedprice, discountrate, expiredate, contributor) VALUES ('$name','$pid','$product','$details','$website','$price_orig','$price_red','$discountr','$expire_date','$uid')") or die (mysql_error());
			$id = mysql_insert_id();
					
			// Make a folder to hold files for user
			$path = "lisstings/$id/";
			if (!is_dir($path)) { mkdir($path); }
			
			move_uploaded_file($_FILES['pic']['tmp_name'], "$path$id.png");
			chmod("$path$id.png", 0644);
				
			header("location: add_lissting?success=l");
				
			}
	}
}


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<title>Add a Lissting</title>
<?php include_once "heading.php" ?>
<style>
.highlight {background: #F4FCDE; border: solid 2px #5D751D; }
</style>
</head>
<body>
<div class='wrap'>

<?php include_once "header.php" ?>

<div class='content'>

		<b class='heading left'>Add a Sale, Coupon, Promotion, Good Value, or Deal</b>
		
		<div class='addform'>
		<p>Tell us as much as you can about this lissting!</p>
		<span id='status'><?php echo $alert ?></span>
		<form id='add' enctype='multipart/form-data' style='width: 450px;' method='post' action='add_lissting'>
		<input type='text' name='page' class='formfield' id='name' placeholder='Who is offering this promotion?' style='width: 400px' value='<?php echo $page ?>' /><br>
		<!--<input type='hidden' name='brandid' value='<?php echo $id ?>' />-->
		<input type='text' name='name' class='formfield' id='name' placeholder='Name of the promotion' style='width: 400px' value='<?php echo $lissting ?>' autofocus/><br>
		<input type='text' name='product' class='formfield' id='product' placeholder='Name of product promoted (if general sale, leave blank)' value='<?php echo $product ?>' style='width: 400px' /><br>
		<textarea name='details' class='formtext' style='width: 400px'>Details or Instructions</textarea>
		<input type='text' name='website' class='formfield' id='website' placeholder='Website for more details' value='<?php echo $website ?>' style='width: 400px' /><br>
		<b>$</b><input type='text' name='price_orig' class='formfield' id='priceo' placeholder='Original price' value='<?php echo $price_orig ?>' style='width: 100px; color: red;' />
		<b>$</b><input type='text' name='price_red' class='formfield' id='pricer' placeholder='Reduced price' value='<?php echo $price_red ?>' style='width: 100px; color: #89A303' />
		<b>&nbsp;&nbsp;&nbsp;OR&nbsp;&nbsp;&nbsp;</b>
		<input type='text' name='discount_rate' class='formfield' id='discountr' placeholder='Discount rate' value='<?php echo $discountr ?>' style='width: 100px' /><b>%</b><br>
		<label for='expires'>When does it expire?&nbsp;&nbsp;</label><input type='date' name='expires' class='formfield' id='expires' value='<?php echo $expire_date ?>' style='width:180px' /><br>
		<label for='pic'>Attach a picture of the clipping!</label><input type='file' name='pic' id='pic' /><br><br>
		<input type='submit' class='button' id='submit' value='Add Lissting!' /><br><br>
		</form>
		</div>

</div>

</div>

<?php include_once "footer.php" ?>

</body>
</html>