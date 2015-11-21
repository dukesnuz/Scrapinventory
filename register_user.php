<?php
/**********************************This is the page format for each page.  I ma using the mvc format******************/
/**********************This page also usee****************************************************************************/
/***********register_user.php*************************************************************************/

include('includes/config.inc.php');


require(MYSQL);

$page_title ='Register User |  '.SITE_NAME.'';
include('./views/header.inc.html');
//include('./views/index.inc.html');


$reg_errors = array();

//This is throwing errors so I used mysqli_real_escape_string
//$fn= escape_data($_POST['first_name'], $dbc);

	  	
//check for form submission
if($_SERVER['REQUEST_METHOD'] === 'POST')
   {
   	  if(preg_match('/^[A-Z\'.-]{2,45}$/i',$_POST['first_name']))
	  {
	  	//$fn= escape_data($_POST['first_name'], $dbc);
		$fn = mysqli_real_escape_string($dbc, $_POST['first_name']);
	  }else{
	  	$reg_errors['first_name'] = 'Please enter your first name!';
	  }
	  
	  if(preg_match('/^[A-Z \'.-]{2,45}$/i', $_POST['last_name']))
	  {
	  	//$ln = escape_data($_POST['last_name'], $dbc);
	  	$ln =mysqli_real_escape_string($dbc, $_POST['last_name']);
	  }else{
	  	$reg_errors['last_name'] = 'Please enter your last name!';
	  }
	  
	  if(preg_match('/^[A-Z0-9]{2,45}$/i', $_POST['username']))
	  {
	  	//$u = escape_data($_POST['username'], $dbc);
	  	  $u = mysqli_real_escape_string($dbc, $_POST['username']);
	  }else{
	  	$reg_errors['username'] = 'Please enter a desired name using only letters and numbers';
	  }
	  
	   if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	  //if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	  {
	  	//$e = escape_data($_POST['email'], $dbc);
		// $e = mysqli_real_escape_string($dbc, $_POST['email']);
		$e = $_POST['email'];
	  }else{
	  	$reg_errors['email'] = 'Please enter a valid email address';
	  }
	  
	  //check for a password and match against the conformed password
	  
	  if (preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,}$/', $_POST['pass1']))
	  {
	  	if($_POST['pass1'] === $_POST['pass2'])
		{
			//$p =$_POST['pass1'];
			 $p = mysqli_real_escape_string($dbc, $_POST['pass2']); 
		}else{
			$reg_errors['pass2'] = 'Your password did not match the confirmed password!';
		}
	  }else{
			$reg_errors['pass1'] = 'Please enter a valid password';
	  }

   //echo 55;
     //if no errors cheack availability of email address and username
   if(empty($reg_errors))
 
	 {
	 		//echo 66;	
	 		$q = "SELECT email, username FROM users 
	 	      WHERE email = '$e' or username = '$u' ";
	 	      $r = mysqli_query($dbc,$q);
			  $rows = mysqli_num_rows($r);
			// echo 77;
	 if($rows === 0)
	//if(4>6)
		{
				
			  	  //no records returned
			  	//add user to databse
	   		//set company_id  and user_id in user
	   		//$cid = substr(time().mt_rand(1,100000),6, 9);
			$cid = substr(time().mt_rand(1,1000000),6,9);
			$uid = substr(mt_rand(100,1000).time(),3,9);
			$ip = $_SERVER['REMOTE_ADDR'];
			
			
	  $q = "INSERT INTO users (username, email, pass, first_name, last_name, user_id, company_id,ip)
	        VALUES( '$u', '$e', '".password_hash($p, PASSWORD_BCRYPT)."', '$fn', '$ln','$uid','$cid','$ip')";
				  
		$r = mysqli_query($dbc, $q);
		 	
		//query created one row, thnak new customer and send an email
		if(mysqli_affected_rows($dbc) === 1)
		{
			
			//set sessions for user
			//$_SESSION['cid'] = $cid;
			$_SESSION['uid'] = $uid;
			$_SESSION['email'] = $e;
			$_SESSION['username'] = $u;
			
			echo '<div class="alert alert-success"><h3>Thank you for registering!</h3></div>
			
		
		  <div class= "alert warning fade in"><h4>Now register your company
		                              <a href="register_company.php">Here<a/></h4></div>';
		  
		  //I added below line
		   echo "<div class='alert alert-success'><p>Welcome committee sent an email to: $e.</p></div>";
		
		
		  
		$body = "$_SESSION[username]\n\n";
		$body .= "Thank you for registering at ".SITE_NAME.".\n\n";
		$body .="We hope you enjoy our website.\n\n";
		$body .="If you have not also registered your company, please log in and register your company at:\n";
		$body .="http://".BASE_URL."/register_company.php\n\n";
		$body .="Follow us on twitter @".SITE_NAME."\n";
		$body .="http://twitter.com/".SITE_NAME."\n";
		$body .="Thank you,\n\n";
		$body .="The team at ".BASE_URL."\n";
		$body .="END Email";
		
		mail($e, 'User Registration Recieved at '.SITE_NAME.'', $body, 'From:'.CONTACT_EMAIL);
		
		//email me
		$body1 =$body;
		$body1 .= "User regigistered a user on ".SITE_NAME."\n";
		$body1 .= "First name: $fn\n";
		$body1 .= "Email: $e\n\n";
		$body1 .= "Email to register\n$body\n\n";
		$body1 .= "END Email";
		
		mail(CONTACT_EMAIL, 'User Registered on '.SITE_NAME.'', $body1, 'From:'.CONTACT_EMAIL);
		
		     
		include('./views/footer.inc.html');
		exit();
		
		//if query did not work create error
		}else{
			trigger_error('You could not be registered due to a system error.
			            We apologize for any inconvience. We will correct the error ASAP.');
		}
		
		}else{
		//if email address or username is unavailable, create errors
	//echo 88;	
       if($rows ===2)
		
		{
				//echo 99;	
				$reg_errors['email'] = 'This email address has already been registered.  If you have
			forgotten your password, use the link at the left to have your password sent to you';
	     $reg_errors['username'] = 'this username has already been registered. Please try another.';
		}else{
			//confirm which item has been registered
			$row= mysqli_fetch_array($r, MYSQL_NUM);
			  if(($row[0] === $_POST['email']) && ($row[1] === $_POST['username']))
			    {
			    	$reg_errors['email'] = 'This email address has already been registered. If
			    	you have forgotten your password, use the link at the left to have your password
			    	sent to you.';
					
					$reg_errors['username'] = 'This username has already been registered with this
					email address.  If
			    	you have forgotten your password, use the link at the left to have your password
			    	sent to you.';
				}elseif($row[0] === $_POST['email'])
				{
					$reg_errors['email'] = 'This email address has already been registered. If
			    	you have forgotten your password, use the link at the left to have your password
			    	sent to you.';
			    }elseif($row[0] === $_POST['username'])
				{
					$reg_errors['username'] = 'This username has already been registered. Please try another';
				}	
		 }//end of $rows == 2 else
		} //end of $rows ===0 IF
		} //end of empty($reg_errors) IF
   }//end if($_SERVER['REQUEST_METHOD']==="POST")
   
   
require_once('includes/form_functions.inc.php');

include('./views/register_user.inc.html');

include('./views/footer.inc.html');
