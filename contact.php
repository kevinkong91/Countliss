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
<title>About Countliss</title>
<?php include_once "heading.php" ?>
</head>
<body>
<div class='wrap'>

<?php include_once "header.php" ?>

<div class='content'>

		<h3 class='heading'>Contact Countliss</h3>
		
		<div class='text'>
		
		<p>Countliss is always open to new thoughts, ideas and feedback. Here are some questions to spark your thoughts:</p>
		
		<ul class='contact-ideas'>
		<li>Do you have another, different, better way for us to collect, organize, store, or utilize information?</li><hr>
		<li>How can we deliver even more value?</li><hr>
		<li>Can Countliss help your individual account needs in any way?</li><hr>
		<li>Have you found a bug in our code? Let us know to help us tidy up our products!</li>
		</ul>
		
		<form id='add' method='post' action=''>
		<input type='email' name='replyto' class='formfield' placeholder='Email (if you want a reply!)' value='<?php echo $email ?>' /><br><br>
		<textarea name='msg' class='formtext'>We would love to hear from you!</textarea><br>
		<input type='button' name='submit' id='submit' value='Wave Hello!' class='button' /><img src='media/loader.gif' id='loading' class='hidden' /><span id='status'><?php echo $error ?></span>
		</form>
		</div>
		
<script>
	$(function(){
		$('#submit').click(function(){
			$('#loading').hide();
			$('#status').empty();
			var msg = $('textarea[name=msg]');
			
			//Simple validation to make sure user entered something
        	//If error found, add hightlight class to the text field
			if (msg.val() == ""){
				msg.addClass('highlight').delay(2000).removeClass('highlight');
			} else { msg.removeClass('highlight'); }
			
			if (msg.val() == "") {
				$('span#status').html("<font id='error'>Please say a few words to submit!</font>").fadeIn(400).delay(1000).fadeOut(1000);
			} else {
			var formdata = $('form#add').serialize();
			$('#loading').show();
			$.ajax({
				url: 'contact_process',
				type: 'POST',
				data: formdata,
				cache: false,
				success: function(response){
					$('#loading').hide();
					$('#status').html(response);
					
					if (response == "<font id='error'>That is not a valid email address!</font>") {
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