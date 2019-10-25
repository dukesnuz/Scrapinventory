<?php
/**********************************This is the page format for each page.  I ma using the mvc format******************/
/**********************This page also usee****************************************************************************/
/***********register.php*************************************************************************/

include('includes/config.inc.php');


require(MYSQL);

$page_title ="Register | Scrapinventory";
include('./views/header.inc.html');
//include('./views/index.inc.html');


$reg_errors = array();


//check for form submission
if($_SERVER['REQUEST_METHOD'] === 'POST')
   {
   	  if(preg_match('/^[A-Z\'.-]{2,45}$/i',$_POST['first_name']))
	  {
	  	$fn= escape_data($_POST['first_name'], $dbc);
	  }else{
	  	$reg_errors['first_name'] = 'Please enter your first name!';
	  }
	  
	  if(preg_match('/^[A-Z \'.-]{2,45}$/i', $_POST['last_name']))
	  {
	  	$ln = escape_data($_POST['last_name'], $dbc);
	  }else{
	  	$reg_errors['last_name'] = 'Please enter your last name!';
	  }
	  
	  if(preg_match('/^[A-Z0-9]{2,45}$/i', $_POST['username']))
	  {
	  	$u = escape_data($_POST['username'], $dbc);
	  }else{
	  	$reg_errors['username'] = 'Please enter a desired name using only letters and numbers';
	  }
	  
	  if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	  {
	  	$e = escape_data($_POST['email'], $dbc);
	  }else{
	  	$reg_errors['email'] = 'Please enter a valid email address';
	  }
	  
	  //check for a password and match against the conformed password
	  
	  if (preg_match('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,}$/', $_POST['pass1']))
	  {
	  	if($_POST['pass1'] === $_POST['pass2'])
		{
			$p =$_POST['pass1']; 
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
					
			      // echo 077;   	
			  	  //no records returned
			  	//add user to databse
	   		//set company_id  and user_id in user
	   		//$cid = substr(time().mt_rand(1,100000),6, 9);
			$cid = substr(time().mt_rand(1,1000000),6,9);
			$uid = substr(mt_rand(100,1000).time(),3,9);
	  $q = "INSERT INTO users (username, email, pass, first_name, last_name, user_id, company_id)
	        VALUES( '$u', '$e', '".password_hash($p, PASSWORD_BCRYPT)."', '$fn', '$ln','$uid','$cid')";
				  
		$r = mysqli_query($dbc, $q);
		 	
		//query created one row, thnak new customer and send an email
		if(mysqli_affected_rows($dbc) === 1)
		{
			echo '<div class="alert alert-success"><h3>Thanks!</h3><p>Thank you for registering!
			     You may now log in and access the site\'s content.</p></div>';
		$body = "Thank you for registering at<No Name Site>. ect. ect\n\n";
		mail($_POST['email'], 'Registration Confirmation', $body, CONTACT_EMAIL);
		
		/*
		//below copied from book
		$body = "Thank you for registering at <whatever site>. Blah. Blah. Blah.\n\n";
		mail($_POST['email'], 'Registration Confirmation', $body, 'From: admin@example.com');
		 */
		 	
	    //I added below line
		echo "<div class='alert alert-success'><h3>Below not in the book.</h3>
		              <p>Welcome Committee sent an email to: $e.</p></div>";
		          
		include('includes/footer.html');
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
