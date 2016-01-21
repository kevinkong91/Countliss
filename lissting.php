<?php

require_once "scripts/checkuser.php";

// if lissting is specified
if (isset($_GET['l'])) {
	$lissting = $_GET['l'];
	
	$check = mysql_query("SELECT * FROM lisstings WHERE id='$lissting' LIMIT 1") or die (mysql_error());
	$count = mysql_num_rows($check);
	if ($count > 0) {
		while ($l = mysql_fetch_array($check)) {
			$pid = $l['pid'];
			$name = $l['name'];
			$product = $l['product'];
			$details = $l['details'];
			$website = $l['website'];
			$priceorig = $l['originalprice'];
			$pricered = $l['reducedprice'];
			$discountrate = $l['discountrate'];
			$expiredate = $l['expiredate'];
			$contributor = $l['contributor'];
		}
		
		// get page info if exists
		if ($pid !== "") {
			$getpage = mysql_query("SELECT * FROM pages WHERE id='$pid' LIMIT 1") or die (mysql_error());
			$count1 = mysql_num_rows($getpage);
			if ($count1 > 0) {
				while ($p = mysql_fetch_array($getpage)) {
					$pname = $p['name'];
				}
			}
		}
		
		// get contributor info if exists
		if ($contributor !== "") {
			$getuser = mysql_query("SELECT * FROM users WHERE id='$contributor' LIMIT 1") or die (mysql_error());
			$count2 = mysql_num_rows($getuser);
			if ($count2 > 0) {
				while ($u = mysql_fetch_array($getuser)) {
					$uname = $u['firstname'];
				}
			}
		}
		
		// get photo if exists
		$checkpic = "lisstings/$lissting/$lissting.png";
		$default = "";
		if (file_exists($checkpic)) {
			$photo = "<img src='$checkpic' style='max-width:450px' />";
		} else {
			$photo = "<img src='$default' />";
		}
		
		// if prices are reduced but no discount rate is set, calculate and set discount rate
		if ($priceorig !== "" && $pricered !== "" && $discountrate == "") {
			$discount = ($pricered / $priceorig)*100;
			$discountrate = substr($discount,0,5);
		}
		
		$content = "<div class='left'>
		<span id='status'><?php echo $alert ?></span>
		<h3 class='heading'>$name</h3>
		<p>$product - $pname</p>
		<p>$details</p>
		Original Price: $$priceorig<br>
		Reduced Price: $$pricered<br>
		Discount Rate: $discountrate%<br>
		Expires: $expiredate<br>
		Contributor: $uname
		</div>
		
		<div class='right'>$photo
		</div>";
	} else {
		$alert = "<font id='error'>This lissting does not exist. Try searching another!</font>";
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

		<?php echo $content ?>

</div>

</div>

<?php include_once "footer.php" ?>

</body>
</html>