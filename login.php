<?php require_once "scripts/checkuser.php" ?>
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
	
		<div class='right login'>
		<b class='heading left'>Sign in</b> <span class='right' style='padding-top: 15px;'>(or <a href='signup' id='switch'>create an account</a>)</span>
		<form id='login'>
		<input type='email' name='email' id='email' class='formfield' placeholder='Email' style='width: 324px' autofocus /><br>
		<input type='password' name='password' id='password' class='formfield' placeholder='Password' style='width: 324px' /><br><br>
		<a href='forgot' class='left'>forgot password?</a><label class='right'><input type='checkbox' name='remember' id='remember' value='yes' class='checkbox' checked='checked' />Remember Me</label><br><br>
		<img src='media/loader.gif' id='loading' class='hidden' /><span id='status'></span><input type='button' class='button right' id='submit' value='Sign in' />
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
			var formdata = $('form#login').serialize();
			$('#loading').show();
			$.ajax({
				url: 'login_process',
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