<?php
/********************************forgot password**********************************
 **************************ecommerce Page 396************************************/
include('includes/config.inc.php');


require(MYSQL);

$page_title ='Forgot Password |  '.SITE_NAME.'';
include('./views/header.inc.html');
//include('./views/index.inc.html');

//echo '<h>1</h1>';
$pass_errors = array();

if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			{
				//escape_data($_POST['email'], $dbc)
				$q ='SELECT user_id FROM users WHERE email = "'. mysqli_real_escape_string($dbc, $_POST['email']) .' " ';
				$r= mysqli_query($dbc, $q);
					if(mysqli_num_rows($r) === 1)
						{
							list($uid) = mysqli_fetch_array($r, MYSQLI_NUM);
						}else{
							$pass_errors['email'] = 'The submitted email address does not match those on file!';
						}
						
						//echo '<h>2</h1>';
			}else{
				$pass_errors['email'] = 'Please enter a valid email address!';
			}//END of $_POST['email'] IF
			if(empty($pass_errors))
				{
					//echo '<h>36</h1>';
					//$p = substr(md5(uniq(rand(), true)), 10, 15);
                    /*******Code from ecommerce page 396********/
                    $token = openssl_random_pseudo_bytes(32);
					$token = bin2hex($token);
					//echo '<h>222</h1>';
					$q= 'REPLACE INTO access_tokens (user_id, token, date_expires)
						VALUES (?,?, DATE_ADD(NOW(), INTERVAL 15 MINUTE))';
							$stmt = mysqli_prepare($dbc, $q);
							mysqli_stmt_bind_param($stmt, 'is', $uid, $token);
							mysqli_stmt_execute($stmt);
							//echo '<h>334</h1>';
							if(mysqli_stmt_affected_rows($stmt) ===1)
								{
									//echo '<h>line 50</h1>';
									
									$url = 'https://'.BASE_URL.'/reset.php?t='.$token;
									
									//send email
									$email = $_POST['email'];
									
									$body ="This email is in response to  a forgotten password reset request at ".SITE_NAME." \n"; 
									$body .="If you did make this request, click the following link to be able to access your account:\n";
									$body .= "$url\n";
									$body .= "For security purposes, you have 15 minutes to do this.  If you do not click this link within 15 minutes,\n";
									$body .= "you will need to request a password reset again.  If you have not forgotten your password, you can\n";
									$body .= "safely ignore this meassage and you will still be able to login with your existing password.\n\n";
									$body .= "Thank you,\n";
									$body .= "Team ".SITE_NAME."\n";
									$body .= BASE_URL;
									$body .= "\nEND Email";
								   
									mail($email,'Password reset @ '.SITE_NAME,$body, 'FROM:'.CONTACT_EMAIL);
									
									
									//mail to me 
									$body1 = 'password reset on '.SITE_NAME.'\n';
									$body1 .="Email: $email\n";
									$body1 .="Message to user: \n$body";
									$body1 .="END Email";
									
									mail(CONTACT_EMAIL,'Password reset @ '.SITE_NAME, $body, 'FROM:'.CONTACT_EMAIL);
									
									echo '<h1>Reset Your Password</h1>
									 		<p>You will recieve an access code via email. Click the link in that email to gain access to the site.
									 		 Once you have done that, you may then change your password.</p>';
									 		 
									 	//echo '<p>This is what the emails will read:</p>';
										//echo "To user: $body";
									 	//echo '<br>';
									 	//echo "To me : $body1";
									 	//echo $url;
									 
									include('./views//footer.inc.html');
									exit();
					
                    /*******END page 396**********/
	         				   }else{ //IF it did not run OK.
		       						 trigger_error('Your password could not be changed due to a system error. We apologize for any inconvenience.');
	           					 }
	
	}//END of $uid if
}//END of the main Submit conditional
	
	
	require_once('./includes/form_functions.inc.php');
	//echo '<h>4</h1>';
	?>
	<h1>Reset Your Password</h1>
	<p>Enter your email address below to reset your password.</p>
	
	<form action="forgot_password.php" method="post" accept-charset="utf-8">
		<?php create_form_input('email', 'email', 'Email Address', $pass_errors);?>
		<input type="submit" class="btn btn-default" />
	</form>


<?php
include('./views/footer.inc.html');

