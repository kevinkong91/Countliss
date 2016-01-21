<?php

require_once "scripts/checkuser.php";

if (isset($_GET['email'])) {
	$email = $_GET['email'];
}

if (isset($_GET['error'])) {
	$error = "<font id='error'>Something went wrong. Try again!</font>";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<title>Create an account</title>
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
		<img src='http://placehold.it/440x600'>
		</div>
	
		<div class='right signup'>
		<b class='heading left'>Create an account</b> <span class='right' style='padding-top: 15px;'>(or <a href='login' id='switch'>sign in</a>)</span>
		<form id='signup'>
		<input type='text' name='fname' class='formfield' id='fname' placeholder='First Name' style='width: 158px' autofocus/>
		<input type='text' name='lname' class='formfield' id='lname' placeholder='Last Name' style='margin-left: -2px; width: 156px' /><br>
		<input type='email' name='email' class='formfield' id='email' placeholder='Email' style='width: 334px' value='<?php echo $email ?>' /><br>
		<input type='password' name='password' class='formfield' id='password' placeholder='Password' style='width: 334px' /><br><br>
		<img src='media/loader.gif' id='loading' class='hidden' /><span id='status'><?php echo $error ?></span><button type='button' class='button right' id='submit' />Create account</button>
		</form>
		</div>
		
		<script>
	$(function(){
		$('#submit').click(function(){
			$('#loading').hide();
			$('#status').empty();
			var fname = $('input[name=fname]');
			var lname = $('input[name=lname]');
			var email = $('input#email');
			var password = $('input#password');
			
			//Simple validation to make sure user entered something
        	//If error found, add hightlight class to the text field
			if (fname.val() == ""){
				fname.addClass('highlight');
			} else { fname.removeClass('highlight'); }
			
			if (lname.val() == ""){
				lname.addClass('highlight');
			} else { lname.removeClass('highlight'); }
			
			if (email.val() == ""){
				$('input#email').addClass('highlight');
			} else { email.removeClass('highlight'); }
			
			if (password.val() == ""){
				password.addClass('highlight');
			} else { password.removeClass('highlight'); }
			
			if (fname.val() == "" || lname.val() == "" || email.val() == "" || password.val() == "") {
				$('span#status').html("<font id='error'>All fields are required!</font>").fadeIn(400).delay(1000).fadeOut(1000);
			} else {
			var formdata = $('form#signup').serialize();
			$('#loading').show();
			$.ajax({
				url: 'signup_process',
				type: 'GET',
				data: formdata,
				cache: false,
				success: function(response){
					$('#loading').hide();
					$('#status').html(response);
					$('#status').fadeIn(400).delay(1000).fadeOut(400);

					if (response == "<font id='success'>Welcome!</font>") {
						var url = "http://pieta.x10.mx";
						$(location).attr('href',url);
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