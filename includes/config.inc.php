<?php
    
    session_start();
     // are we live
     //use below for devlopement
     if(!defined('LIVE')) DEFINE('LIVE', false);
	 //use below for live
	 //if(!defined('LIVE')) DEFINE('LIVE', true);
	
	//errors emailed here
	//DEFINE('CONTACT_EMAIL', 'hello@scrapinventory.net');
	DEFINE('CONTACT_EMAIL', 'hello@scrapinventory.net');
	DEFINE('CONTACT_EMAIL_2', 'david@ajaxtransport.com');
	DEFINE('SITE_NAME' , 'Scrapinventory');
	
	//determine location of files and the URL of the site:
	//define('BASE_URI', 'C:\xampp\htdocs\dukesnuz\phppercolate_7\ecom-2');
	
	//check if live 
	if(LIVE === true)
	{
		define('BASE_URL','www.scrapinventory.net');
		define('BILLING_URL', 'https://www.scrapinventory.net/billing.php');
	}else{
	   define('BASE_URL', 'localhost/scrapinventory/');
	   define('BILLING_URL', 'localhost/scrapinventory/billing.php');
	}


     //define connection to database                   
     define('MYSQL', './include_2/mysql_connect.php');
       define('MYSQL_2', '../include_2/mysql_connect.php');
    //define url link when using MOD_WRITE
   // define('MODWRITE', '/d/phppercolate_7/ecom-2/ch_15');
	

	define('BASIC_FEE','$84.00');
	

	
	function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars)
	{
		//build error message
		$message = "An error occurred in script '$e_file' on line $e_line:\n$e_message\n";
		//add backtrace
		$message .="<pre>".print_r(debug_backtrace(),1). "</pre>\n";
		
		//or just append $e_vars to the message:
		//$message .="<pre>" .print_r ($e_vars, 1) . "</pre>\n";
		
		if(!LIVE)
			{
				//show error in browser
				echo '<div class="error">' . nl2br($message) . '</div>';
			}else{
				//developement print error
				//send error in an email
				error_log ($message, 1, CONTACT_EMAIL, CONTACT_EMAIL);
				
				//only print error in browser, if error isnt a notice
				if($e_number != E_NOTICE)
					{
						echo ' <div class="error">A System error occurred.  We apologize for the 
						inconvenience.</div>';
					}
			}//End of $live IF_ELSE.
			return true; //So that PHP does nt try to handle the error, too.
	} //End of my_error_handler() definition
//Use my error handler
set_error_handler('my_error_handler');



//redirect if not logged in
function redirect_invalid_user($check = 'uid', $destination ='index.php', $protocol ='http://')
  {
	if(!isset($_SESSION[$check]))
		{
			$url = $protocol .BASE_URL.$destination;
			header("Location:$url");
			exit();
		}
  }

 
 	//below used for stripe Below for development
	define('STRIPE_PRIVATE_KEY','sk_test_4bd5iZmJncdWOVYOq3zTckmL');
	define('STRIPE_PUBLIC_KEY', 'pk_test_0hWWXi7BaZ5XkpbLFNvEhgaa');
	
	//below for live
	//define('STRIPE_PRIVATE_KEY','sk_live_ZnMBKYMBIUSQQUNvYD87pJxp');
	//define('STRIPE_PUBLIC_KEY', 'pk_live_ykQ7mH70ebMMSbOJjY72nFUS');
	
	
	
 //define constants for html page 210

define('BOX_BEGIN', '<!--box begin--><div class="box alt">
		<div class="left-top-corner">
		<div class="right-top-corner">
		<div class="border-top">
		</div></div></div>
		<div class="border-left">
		<div class="border-right">
		<div class="inner">');
define('BOX_END', '</div></div></div>
		<div class="left-bot-corner">
		<div class="right-bot-corner">
		<div class="border-bot">
		</div></div></div></div>
		<!--box end-->'); 
  