<?php
 /***************************login_form.inc.php************************************/
if(!isset($login_errors)) 
	{
		$login_errors = array();
	}

	require('./includes/form_functions.inc.php');
?>

<<<<<<< HEAD
<form action ="/home/" method="post" accept-charset="UTF-8">
=======
<form action ="index.php" method="post" accept-charset="UTF-8">
>>>>>>> 10ab8b6629f9210eae104f200e890fe17b30716a
	<fieldset>
		<legend>Login</legend>
		<?php
			if(array_key_exists('login',$login_errors))
				{
					echo '<div class="alert alert-danger">'.$login_errors["login"].'</div>';
				}
				
			create_form_input('email','email','',$login_errors, array('placeholder'=>'Email address'));
			create_form_input('pass','password','', $login_errors, array('placeholder' =>'Password'));
		?>
		<button type="submit" class="btn btn-default">Login &rarr;</button>
	</fieldset>
</form>


<!--I added below line-->
<<<<<<< HEAD
<p><a href="/forgot-password/">Forgot Password</a></p>
=======
<p><a href="forgot_password.php">Forgot Password</a></p>
>>>>>>> 10ab8b6629f9210eae104f200e890fe17b30716a
