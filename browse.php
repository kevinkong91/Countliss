<?php

require_once "scripts/checkuser.php";

	$check = mysql_query("SELECT * FROM lisstings ORDER BY expiredate LIMIT 8") or die (mysql_error());
	$count = mysql_num_rows($check);
	if ($count > 0) {
		while ($l = mysql_fetch_array($check)) {
			$id = $l['id'];
			$pid = $l['pid'];
			$name = $l['name'];
			$product = $l['product'];
			$website = $l['website'];
			$priceorig = $l['originalprice'];
			$pricered = $l['reducedprice'];
			$discountrate = $l['discountrate'];
			$expiredate = $l['expiredate'];
			$contributor = $l['contributor'];
		
		
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
		$checkpic = "lisstings/$id/$id.png";
		$default = "";
		if (file_exists($checkpic)) {
			$photo = "<img src='$checkpic' style='max-width:70px; max-height:70px' />";
		} else {
			$photo = "<img src='$default' />";
		}
		
		
		$each_lissting .= "<div class='each-browse'>
			$photo
			<a href='lissting?l=$id'>$name</a>
			<p>$product - $pname</p>
			Original Price: $$priceorig<br>
			Reduced Price: $$pricered<br>
			Discount Rate: $discountrate%<br>
			Expires: $expiredate<br>
			Contributor: $uname
			</div>";
		}
		
		$content = "$each_lissting";
	} else {
		$alert = "<font id='error'>This lissting does not exist. Try searching another!</font>";
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