<?php

require_once "scripts/checkuser.php";

if (isset($_GET['name'])) {
	$email = $_GET['name'];
}

if (isset($_GET['error'])) {
	$error = "<font id='error'>Something went wrong. Try again!</font>";
}

$sql = mysql_query("SELECT * FROM industries") or die (mysql_error());
$count = mysql_num_rows($sql);
if ($count > 0) {
	while ($i = mysql_fetch_array($sql)) {
		$iid = $i['id'];
		$category = $i['category'];
		$industry = $i['industry'];
		
		$options .= "<option value='$category - $industry'>$category - $industry</option>";
	}
	
	$industry = "<select name='industry' class='formselect' style='width:418px'><option>Select Industry</option>$options</select>";
}


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<title>Add Page</title>
<?php include_once "heading.php" ?>
<style>
.highlight {background: #F4FCDE; border: solid 2px #5D751D; }
</style>
</head>
<body>
<div class='wrap'>

<?php include_once "header.php" ?>

<div class='content'>

		<h3 class='heading'>Add a Page<span class='right' style='padding-top: 15px;'>(or contribute some <a href='add_lissting'>lisstings</a>!)</span></h3>
		
		<div class='left addform' style='width: 430px;'>
		<p>Tell us as much as you can about this Brand, Business, Organization, or Artist!</p>
		<form id='add' enctype='multipart/form-data'>
		<input type='text' name='name' class='formfield' id='name' placeholder='Name' style='width: 400px' autofocus/><br>
		<?php echo $industry ?><br>
		<input type='text' name='website' class='formfield' id='website' placeholder='Website' style='width: 400px' /><br><br>
		<button type='button' class='button' id='submit' />Add Page!</button><br>
		<img src='media/loader.gif' id='loading' class='hidden' /><span id='status'><?php echo $error ?></span>
		</form>
		</div>

		<div class='right white'>
		<img src='http://placehold.it/300x600'>
		</div>
		
		<script>
	$(function(){
		$('#submit').click(function(){
			$('#loading').hide();
			$('#status').empty();
			var name = $('input[name=name]');
			
			//Simple validation to make sure user entered something
        	//If error found, add hightlight class to the text field
			if (name.val() == ""){
				name.addClass('highlight');
			} else { name.removeClass('highlight'); }
			
			if (name.val() == "") {
				$('span#status').html("<font id='error'>A name is a must!</font>").fadeIn(400).delay(1000).fadeOut(1000);
			} else {
			var formdata = $('form#add').serialize();
			$('#loading').show();
			$.ajax({
				url: 'add_page_process',
				type: 'POST',
				data: formdata,
				cache: false,
				success: function(response){
					$('#loading').hide();
					$('#status').html(response);
					
					if (response == "<font id='error'>That page already exists! Try adding another.</font>") {
						$('#status').fadeIn(400).delay(1000).fadeOut(400);
					} else {
						$('#status').fadeIn(400);
					}
				}
			});
			}
			return false;
		});
	});
	</script>

</div>

</div>

<?php include_once "footer.php" ?>

</body>
</html>