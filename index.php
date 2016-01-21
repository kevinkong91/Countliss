<?php require_once "scripts/checkuser.php" ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<title>pieta</title>
<?php include_once "heading.php" ?>
</head>
<body>
<div class='wrap'>

<div class='header-wrap'>
<div class='left'>
	<ul class='left'>
	<li><a href=''></a></li>
	<li></li>
	<li></li>
	<li></li>
	<li></li>
	</ul>
</div>
<div class='right'>
	<?php echo $thumb ?>
</div>
</div>

<div class='center'>
	<h1><b class='logo main'>countliss</b></h1>
	<input type='text' name='s' class='search' placeholder='Search brands, dates, etc.' autofocus />
	<p>or <a href='browse'>browse</a>!</p>
</div>

</div>

<?php include_once "footer.php" ?>

</body>
</html>