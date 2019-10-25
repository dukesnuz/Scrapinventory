<?php
/**********************************This is the page format for each page.  I ma using the mvc format******************/
/**********************This page also usee****************************************************************************/
/***********register_user.php*************************************************************************/

include('includes/config.inc.php');


require(MYSQL);

$page_title ='Say Hello |  '.SITE_NAME.'';
include('./views/header.inc.html');
//include('./views/index.inc.html');


$reg_errors = array();

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
	  
	  
	 if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	  //if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	  {
	  	//$e = escape_data($_POST['email'], $dbc);
		// $e = mysqli_real_escape_string($dbc, $_POST['email']);
		$e = $_POST['email'];
	  }else{
	  	$reg_errors['email'] = 'Please enter a valid email address';
	  }
	  
	 if(preg_match('/^[A-Z 0-9 \',.-]{2,500}$/i', $_POST['message']))
		{
			//$c_about = escape_data($_POST['c_about'], $dbc);
				$message = mysqli_real_escape_string($dbc, $_POST['message']);
		}else{
			$reg_errors['message'] = 'Please enter your message.';
		}
	  
	 
   if(empty($reg_errors))
 
	 {
			 	
	 	
		if(isset($_SESSION['cid']))
			{
				$cid = $_SESSION['cid'] ;
			}else{
				$cid=Null;
			}
			if(isset($_SESSION['uid']))
			{
				$uid= $_SESSION['uid'];
			}else{
				$uid= Null;
			}
			$ip = $_SERVER['REMOTE_ADDR'];
			
			
	 	 $q = "INSERT INTO contact (email, first_name, type, message, user_id, company_id, ip,page)
	        VALUES( '$e', '$fn', 'contact','$message','$uid','$cid','$ip','contact.php')";
				  
		$r = mysqli_query($dbc, $q);
		 	
		//query created one row, thnak new customer and send an email
		if(mysqli_affected_rows($dbc) === 1)
		{
			
		  //I added below line
	      echo "<div class='alert alert-success'><h3>Thank you for contacting us.</h3>
					<p>We will respond back to you as fast as we can.</p></div>";
		  
		  
		$body = "$fn\nThank you for contacting us.\nWe will respond back to you as fast as we can.\nTeam ".SITE_NAME."\n".BASE_URL."\nEND Email";
		
		mail($e, 'Thank you for contacting '.SITE_NAME, $body, "From:".CONTACT_EMAIL);
		
		
		//Send email to me
		$body = "Message came in on contact.php page, ".SITE_NAME."\n";
		$body .="Email: $e\n";
		$body .="First Name: $fn\n";
		$body .="Message:\n";
		$body .="$message\n";
		$body .="END Email";
		
		mail(CONTACT_EMAIL, SITE_NAME.' Message', $body, "From:".CONTACT_EMAIL);
		 
		 	
	    
		          
		include('./views/footer.inc.html');
		exit();
		
		//if query did not work create error
		}else{
			trigger_error('You could not be registered due to a system error.
			            We apologize for any inconvience. We will correct the error ASAP.');
		 }//end if mysqli_affected rows
     }//END if no errors
}//end if($_SERVER['REQUEST_METHOD']==="POST")		
  
require_once('includes/form_functions.inc.php');

include('./views/contact.inc.html');

include('./views/footer.inc.html');
		
   	