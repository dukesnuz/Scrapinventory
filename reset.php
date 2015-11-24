<?php
/********************reset.php**
 * *****ecommerce page 399******************************/

include('includes/config.inc.php');


require(MYSQL);
//echo 1;
$page_title ='Reset Your Password |  '.SITE_NAME.'';
include('./views/header.inc.html');
//include('./views/index.inc.html');

$reset_error='';
$pass_errors = array();


//check for toke in url
if(isset($_GET['t']) && (strlen($_GET['t']) ===64))

	{
		$token= $_GET['t'];

	
	//grab uer id //AND date_expires > NOW()
	/*$q= 'SELECT user_id FROM access_tokens WHERE token =? ';
    
	$stmt = mysqli_prepare($dbc, $q);
	mysqli_stmt_bind_param($stmt, 's', $token);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_store_result($stmt);
 
		if(mysqli_stmt_num_rows($stmt) ===1)
		*/
		$q = "SELECT user_id, company_id,username FROM access_tokens WHERE token ='$token' AND date_expires > NOW()";
			
			$r = mysqli_query($dbc, $q);
			
				if(mysqli_num_rows($r) === 1)
					{
					 $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
				 
							//mysqli_stmt_bind_result($stmt, $uid);
							//mysqli_stmt_fetch($stmt);
						$uid = $row['user_id'];
						$cid = $row['company_id'];
						//session_regenerate_id(true);
						
						$_SESSION['uid'] = $uid;
						$_SESSION['cid'] = $cid;
						$_SESSION['username']=$row['username'];
				/***************Check if user expired stored in companies***********/
								//IF(date_expires >= NOW(), true, false) AS expired 
								$q = "SELECT IF(date_expires >= NOW(), true, false) AS expired FROM companies
									WHERE company_id = '$cid'  ";
									        // test cid -> 346193241
										$r = mysqli_query($dbc, $q);
			
										if(mysqli_num_rows($r) === 1)
										  {
										   $row =mysqli_fetch_array($r, MYSQLI_ASSOC);
							   
										  if($row['expired'] === '1') 
										      {
												$_SESSION['user_not_expired'] = 'true';
											  }
										 }
				/***************Check if user expired stored in companies***********/					 
				//clear token form database
				$q = 'DELETE FROM access_tokens WHERE token=?';
				$stmt = mysqli_prepare($dbc, $q);
				mysqli_stmt_bind_param($stmt, 's', $token);
				mysqli_stmt_execute($stmt);
				
				//echo $uid;
			}else{
			$reset_error = 'Either the provided token does not match that on file or your time has expired.
							Please resubmit the "Forgot your password" form.';
							 
			}
		}else{//NO token
		$reset_error = 'This page has been accessed in error.';
		}
	
 	
		if(($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_SESSION['uid']))
			{
				$reset_error = '';
                   if (preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,}$/', $_POST['pass1']))	
				   		{
						if($_POST['pass1'] == $_POST['pass2'])
							{
								$p = $_POST['pass1'];
							}else{
								$pass_errors['pass2'] = 'Your password did not match the confirmed password!';
							}						
					}else{
						$pass_errors['pass1'] = 'Please enter a valid password';
					}
						
		if(empty($pass_errors))
				{
					$q = 'UPDATE users SET pass=? 
							WHERE user_id = ?
							LIMIT 1';
							
							$stmt = mysqli_prepare($dbc, $q);
							mysqli_stmt_bind_param($stmt, 'si', $pass, $_SESSION['uid']);
							$pass = password_hash($p, PASSWORD_BCRYPT);
							mysqli_stmt_execute($stmt);
							
							
							if(mysqli_stmt_affected_rows($stmt) === 1)
								{
									echo '<h1>Your password has been changed.</h1>';
									include('./views/footer.inc.html');
									exit();
									
								}else{
									//IF it did not run ok
									trigger_error('Your password could not be changed due to a system error.
													We apologize for any inconvenience.');
								}
			         	}//END if pass errors
			         	
					}elseif($_SERVER['REQUEST_METHOD'] ==='POST')
					{
						$reset_error = 'This page has been accessed in error.';
					}//END form submission
	
	//echo 99;				
		if(empty($reset_error))
			{
				require_once('./includes/form_functions.inc.php');
				?>
				<h1>Change your password</h1>
				
				<p>Use the form below to change your password.</p>
				
				<form action = "reset.php" method="post" accept-charset="utf-8">
					<?php	
					create_form_input('pass1', 'password','Password', $pass_errors);
					echo '<span class="help-block">Must be at least 6 characters long, with at 
					       least one lowercase letter, one uppercase letter and one number.</span>';
					create_form_input('pass2', 'password', 'Confirm Password', $pass_errors);
					?>
					<input type="submit" name="submit_button" value="Change &rarr;" id="submit_button" class="btn btn-default" />
				</form>
			<?php
				}else{
					echo'<div class="alert alert-danger">'.$reset_error.'</div>';
			}
	          
			include('./views/footer.inc.html');
			?>
