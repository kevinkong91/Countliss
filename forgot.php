<?php

require_once "scripts/checkuser.php";

if (isset($_GET['email'])) {
	$email = $_GET['email'];
}

 ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<title>Forgot Password</title>
<?php include_once "heading.php" ?>
<style>
.highlight {background: #F4FCDE; border: solid 2px #5D751D; }
</style>
</head>
<body>
<div class='wrap'>

<?php include_once "header.php" ?>

<div class='content'>

		<div class='left white'>
		<img src='http://placehold.it/410x600'>
		</div>
	
		<div class='right login' style='width:380px'>
		<span class='heading bold'>Forgot Password</span>
		<p>No worries! It happens to the best of us. We'll send you a reset link.</p>
		<form id='forgot'>
		<input type='email' name='email' class='formfield' placeholder='Email' style='width: 334px' value='<?php echo $email ?>' autofocus /><br><br>
		<img src='media/loader.gif' id='loading' class='hidden' /><span id='status'></span><input type='button' class='loginbutton right' id='submit' value='Send!' />
		</form>
		
		</div>

		</div>
		<script>
	$(function(){
		$('#submit').click(function(){
			$('#loading').hide();
			$('#status').empty();
			var email = $('input#email');
			var password = $('input#password');
			
			//Simple validation to make sure user entered something
        	//If error found, add hightlight class to the text field
			if (email.val() == ""){
				$('input#email').addClass('highlight');
			} else { email.removeClass('highlight'); }
			
			if (password.val() == ""){
				password.addClass('highlight');
			} else { password.removeClass('highlight'); }
			
			if (email.val() == "" || password.val() == "") {
				$('span#status').html("<font id='error'>All fields are required!</font>").fadeIn(400).delay(1000).fadeOut(1000);
			} else {
			var formdata = $('form#forgot').serialize();
			$('#loading').show();
			$.ajax({
				url: 'forgot_process',
				type: 'GET',
				data: formdata,
				cache: false,
				success: function(response){
					$('#loading').hide();
					$('#status').html(response);
					$('#status').fadeIn(400).delay(1000).fadeOut(400);
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