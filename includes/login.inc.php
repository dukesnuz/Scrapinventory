<?php
/*****************************login.inc/php*************************************************************/

$login_errors = array();
if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	{
		//$e = escape_data($_POST['email'], $dbc);
		$e= $_POST['email'];
		//$e = mysqli_real_escape_string($dbc, $_POST['email']);
	}else{
		$login_errors['email'] = 'Please enter a valid email address!';
	}
	
	if(!empty($_POST['pass']))
		{
			$p = $_POST['pass'];
		}else{
			$login_errors['pass'] = 'Pleae enter your password!';
		}
	
	if(empty($login_errors))
		{
			$q ="SELECT user_id, email, username, type, pass,company_id, IF(date_expires >= NOW(), true, false) AS expired FROM users WHERE email ='$e'";
			$r = mysqli_query($dbc, $q);
			
			if(mysqli_num_rows($r) ===1)
				{
					$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
					  if(password_verify($p, $row['pass']))
				
						{
							$_SESSION['uid'] = $row['user_id'];
							$_SESSION['username'] = $row['username'];
							$_SESSION['type']  =$row['type'];
							$_SESSION['cid'] = $row['company_id'];
							$_SESSION['email'] = $row['email'];
							if($row['expired'] === '1') 
									{
										$_SESSION['user_not_expired'] = true;
									}
							}else{	
									$login_errors['login'] = 'The email address and password do not match those on file.';
								}
						}else{
							$login_errors['login'] = 'The email address and password do not match those on file';
						}
				}//END of $login_errors IF
	
