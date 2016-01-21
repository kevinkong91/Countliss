<?php

require_once "scripts/checkuser.php";

// Get the name of the page
$name = $_GET['name'];

// Logo/Photo Form Handler
if (isset($_POST['page_name'])) {
	require "scripts/connect_to_mysql.php";
	
	$page_name = mysql_real_escape_string($_POST['page_name']);
	if ($page_name !== "") {
		// Check if the page exists
		$check = mysql_query("SELECT * FROM pages WHERE name='$page_name' LIMIT 1") or die (mysql_error());
		$count = mysql_num_rows($check);
		if ($count > 0) {
			while ($l = mysql_fetch_array($check)) {
				$id = $l['id'];
			}
			
			// Put the photo into the file
			if ($_FILES['logo']['tmp_name'] !== "") {
				move_uploaded_file($_FILES['logo']['tmp_name'], "pages/$id/$id.png");
				chmod("pages/$id/$id.png", 0755);
				
				header("location: add_lissting?page=$page_name&source=newliss");
			}
		}
	}
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<title>Add a Page Photo</title>
<?php include_once "heading.php" ?>
<style>
.highlight {background: #F4FCDE; border: solid 2px #5D751D; }
</style>
</head>
<body>
<div class='wrap'>

<?php include_once "header.php" ?>

<div class='content'>
		
		<div>
		<h4 class='heading'>Let's give this page a beautiful picture!<span class='right' style='padding-top: 15px;'>(or contribute some <a href='add_lissting'>lisstings</a>!)</span></h4>
		</div>
		
		<div class='left addform'>
		<h1><?php echo $name ?></h1><br>
		<form id='add' enctype='multipart/form-data' method='post' action='add_page_photo'>
		<input type='hidden' name='page_name' value='<?php echo $name ?>' />
		<label for='logo'>Attach a colorful logo:</label>&nbsp;&nbsp;&nbsp;<input type='file' name='logo' id='logo' />
		<br><br>
		<button type='submit' class='button' id='submit' />Add Photo!</button><br>
		</form>
		</div>

		<div class='right white'>
		<img src='http://placehold.it/350x600'>
		</div>

</div>

</div>

<?php include_once "footer.php" ?>

</body>
</html>