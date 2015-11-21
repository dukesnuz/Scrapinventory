<?php
/********************************change_password.php*************************************/
include('includes/config.inc.php');

//$_SESSION['uid'] = 1;
redirect_invalid_user();
require(MYSQL);

$page_title ='Change Password |  '.SITE_NAME.'';
include('./views/header.inc.html');
//include('./views/index.inc.html');

$pass_errors = array();
if($_SERVER['REQUEST_METHOD'] ==='POST')
	{
		if(!empty($_POST['current']))
			{
				$current = $_POST['current'];
			}else{
				$pass_errors['current'] = 'Please enter your current password!';
			}

	
	//validate new password
	if(preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,}$/', $_POST['pass1']))
		{
		 	if($_POST['pass1'] === $_POST['pass2'])
		 		{
					$p= $_POST['pass1'];
				}else{
					$pass_errors['pass2'] = 'Your password did not match the confirmed password!';
				}
		}else{
			$pass_errors['pass1'] = 'Please enter a valid password!';
		}
		
		if(empty($pass_errors))
			{
				$uid =$_SESSION['uid'];
				$q = "SELECT pass FROM users 
						WHERE
						user_id = '$uid'";
						$r = mysqli_query($dbc, $q);
						list($hash) = mysqli_fetch_array($r, MYSQLI_NUM);
							if(password_verify($current, $hash))
						
								{
									//update database with new pass
									$q = "UPDATE users SET pass='".password_hash($p, PASSWORD_BCRYPT)."'
										  WHERE
										  user_id ={$_SESSION['uid']}
										  LIMIT 1";
										if($r = mysqli_query($dbc, $q))
											{
												echo '<h1>Your password has been changed!</h1>';
												//echo $_SESSION['uid'];
												include('./views/footer.inc.html');
												exit();
											}else{
												trigger_error('Your password could not be changed due to a system error.
																We apologize for any inconvenience.');
											}
								}else{
									//invalid password
									$pass_errors['current'] = 'Your current password is incorrect!';
								}
			}//END of empty ($pass_errors)IF
	}//END of the form submission conditional
	
	
//displayy the form


require_once('includes/form_functions.inc.php');

include('./views/change_password.inc.html');

include('./views/footer.inc.html');


