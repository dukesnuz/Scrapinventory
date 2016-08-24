<?php
/**********************************This is the page format for each page.  I ma using the mvc format******************/
/**********************This page also usee****************************************************************************/
/***********billing.php*************************************************************************/
// billing.php

 require('includes/config.inc.php');
 require(MYSQL);
 /************for dev******/
  //$_SESSION['email']= "buy@ajaxloft.com";
 //$_SESSION['uid']= 1;
 //$_SESSION['cid'] = 1;
 //$_SESSION['username'] = "username";
 //$_SESSION['user_not_expired'] = true;
 //$_SESSION['user_not_expired'] = false;
 //start seesion and get session ID
 //session_start();

 //redirect invalis users
// if(!isset($_SESSION['customer_id']))
  if(!isset($_SESSION['cid']) || !isset($_SESSION['uid']) )
//if(4>6)
 	{
		$location = 'https://'.BASE_URL .''.MODWRITE .'/index.php';
		header("Location:$location");
		exit();
 	}

if(isset($_SESSION['user_not_expired']) )
			{
			$page_title = 'Billing | '.SITE_NAME.'';
            include('./views/header.inc.html');	
	        echo "<p  class='alert alert-success'>Your account is up to date</p>";
			include('./views/footer.inc.html');
	        exit;
 	        }		
				
				



//set variable
 //$_SESSION['uid'] = 1;
 //$_SESSION['cid'] = 1;
 //$_SESSION['email']  ="buy@ajaxloft.com";
 
 $order_id = 1;
 
 $find = array("$", ".");
 $order_total = str_replace($find,'', BASIC_FEE);

 $order_id =mt_rand(1000000, 10000000);
 $email = $_SESSION['email'];
 $uid = $_SESSION['uid'];
 $cid = $_SESSION['cid'];
 $type = "basic";

  
  
$billing_errors = array();

//check for form submission
if($_SERVER['REQUEST_METHOD'] ==='POST')
	{
		/*page 318 use if magic quotes enabled..I do not believe they are on my server
		 */
		 /*if(get_magic_quotes_gpc() )
			{
				$_POST['cc_first_name'] = stripslashes($_POST['cc_first_name']);
			}
			*/
			if(preg_match('/^[A-Z \'.-]{2,20}$/i', $_POST['cc_first_name']))
				{
					$cc_first_name = $_POST['cc_first_name'];
				}else{
					$billing_errors['cc_first_name'] ='Please enter your first name!';
				}
				
			if(preg_match('/^[A-Z \'.-]{2,40}$/i', $_POST['cc_last_name']))
				{
					$cc_last_name = $_POST['cc_last_name'];
				}else{
					$billing_errors['cc_last_name'] ='Please enter your last name!';
				}
				
	//Stripe chp 15 page 508
	 
				if(isset($_POST['token']))
					{
						$token = $_POST['token'];
					}else{
						$message= 'OOppss! The order cannot be processed. Please make sure you have JavaScript
						enabled and try again.';
						$billing_errors['token'] = true;
						
					}
				//End stripe
				
 
//validate street address
if(preg_match('/^[A-Z0-9 \',.#-]{2,160}$/i',  $_POST['cc_address']))
	{
		$cc_address = $_POST['cc_address'];
	}else{
		$billing_errors['cc_address'] = 'Please enter your street address !';
	}
	
//validate city, state and zop

if(preg_match ('/^[A-Z \'.-]{2,60}$/i', $_POST['cc_city']))
	{
		$cc_city = $_POST['cc_city'];
	}else{
		$billing_errors['cc_city'] = 'Please enter your city!';
	}
		
//if(preg_match ('/^[A-Z]{2}$/', $_POST['cc_state']))
if(preg_match('/^[A-Z]{2}$/', $_POST['cc_state']))
	{
		$cc_state = $_POST['cc_state'];
	}else{
		$billing_errors['cc_state'] ='Please enter your state!';
	}
	
 if (preg_match ('/^(\d{5}$)|(^\d{5}-\d{4})$/', $_POST['cc_zip']))

	{
	$cc_zip = $_POST['cc_zip'];
	}else{
		$billing_errors['cc_zip']= 'Please enter your zip code !';
	}

//if no errors convert expiration date to the correct format
 //echo $billing_errors;
 //echo 'no errors check start';
               // echo 'order id'.$_SESSION['order_id'];
				//echo 'order total'.$_SESSION['order_total'];
				//echo 'customer id'.$_SESSION['customer_id'];
if(empty($billing_errors))
	{
	 
		//check for existing ID in session
		if( isset($_SESSION['order_id'])  && isset($_SESSION['order_total']) )
			{
				//echo 'if session order id ';
				//echo 'order id'.$_SESSION['order_id'];
				//echo 'order total'.$_SESSION['order_total'];
				//echo 'customer id'.$_SESSION['customer_id'];
				$order_id = $_SESSION['order_id'];
				$order_total = $_SESSION['order_total'];
			}else{
				$cc_last_four = 1234;
				//echo 'else last 4';
				//store order
//echo $_SESSION['shipping'];


/*
//echo 'hi';
$r = mysqli_query($dbc, "CALL add_order(
{$_SESSION['customer_id']}, '$uid' , $shipping, $cc_last_four, @total, @oid)");
//if(!$r) echo mysqli_error($dbc);	

		if($r)
		//if(4>2)
			{
				$r = mysqli_query($dbc, 'SELECT @total, @oid');
				
				   if(mysqli_num_rows($r) ==1)
					//if(4>2)
						{
							list($order_total, $order_id) = mysqli_fetch_array($r);
							
							//store order id and totla in session
							$_SESSION['order_total'] = $order_total;
							$_SESSION['order_id'] = $order_id;
						}else{//could not retrieve the order ID and total
						unset($cc_number, $cc_cvv, $_POST['cc_number'], $_POST['cc_cvv']);
						trigger_error('OOppss! Your order could not be processed due to a system error.
						We apologize for the inconvenience.');
						}
									
			}else{
				//the add_order() procedure failed
				unset($cc_number, $cc_cvv, $_POST['cc_number'], $_POST['cc_cvv']);
				trigger_error('Your order could not be processed due to a system error. 
				We apologize for the inconvenience.');			
			}
 */
	}//End of isset($_SESSION['order_id']} IF-ELSE
	
	

	
	//check order ID and total are set
	if(isset($order_id, $order_total))
		{
				
		try{
			require_once('includes/lib/Stripe.php');
			Stripe:: setApiKey(STRIPE_PRIVATE_KEY);
		/*	$charge =Stripe_Charge::create(array(
				'amount' => $order_total,
				'currency' => 'usd',
				'card' => $token,
				'description' => $_SESSION['email'],
				'capture' => false
				     )
			    )
		*/
	
			$charge = Stripe_Customer::create(array(
					'description' => "Customer $email",
					'email' => $email,
					'card' => $token,
					'plan' => 'scrapinventory_basic'
				));
				//test success of the operation
				
				if($charge->paid = true)
				//if($charge->status === "succeeded")
				//if($charge->id > 1)
				//if(4>2)
					{
						
						$full_response = addslashes(serialize($charge));
						
						//add transaction to data base

					
$r = mysqli_query($dbc, "CALL add_charge('{$charge->id}', '$order_id', '$uid', '$cid', '$order_total', '$full_response', '$type')");
					   
					     //if(!$r) echo '<h1>'.mysqli_error($dbc).'</h1>';
						
						/************************UPDATE User expired*********************/
$q = "UPDATE companies SET date_expires = IF(date_expires > NOW(), ADDDATE(date_expires, INTERVAL 1 YEAR), ADDDATE(NOW(), INTERVAL 1 YEAR)), date_modified=NOW() WHERE company_id=$cid";
						$r = mysqli_query($dbc, $q);
						if (mysqli_affected_rows($dbc) !== 1) 
						{
							trigger_error('The user\'s expiration date could not be updated!');
						} 
						 
						 
						 //add sessions to be used in final.php page
					    $_SESSION['cid'] = $cid;
						$_SESSION['oid'] = $order_id;
						$_SESSION['otl'] = $order_total;
						//add transaction info to session
						$_SESSION['response_code']= $charge->paid;
		
						
		/************************send emails***************************************/
		$body =  "$_SESSION[username]\n\n";
		$body .= "Thank you for subscribing to ".SITE_NAME."\n\n";
		$body .= "Your credit card will be charged ".BASIC_FEE." yearly. You may cancel at anytime.\n";
		$body .= "We hope you enjoy our website.\n";
		$body .= "Follow us on twitter @".SITE_NAME."\n";
		$body .= "http://twitter.com/".SITE_NAME."\n";
		$body .= "Thank you\n";
		$body .= "The team at \n".BASE_URL."\n";
		$body .= "END Email";
		
		mail($email, ''.SITE_NAME.'Recieved Your Payment', $body, 'From:'.CONTACT_EMAIL);
		
		//email me
		$body1  = "Payment recieved at ".SITE_NAME.".\n";
		$body1 .= "User email: $email\n";
		$body1 .= "Payment amt: ".BASIC_FEE."\n\n";
		$body1 .= "Email to register\n$body\n\n";
		$body1 .= "END Email";
		
		mail(CONTACT_EMAIL, 'Billing recieved '.SITE_NAME.'', $body1, 'From:'.CONTACT_EMAIL);
		/****************************************END send emails********************************/
		
		
		
		
						//redirect customer to next page
						// $location = 'https://'.BASE_URL_2.'/'.MODWRITE.'/final.php';
						$location = '/final/';
						 header("Location: $location");
	
						exit();
					 }else{
						//if no charge was made , alert customer
						$message = $charge->failure_message;
						//$message = $customer->failure_message;
						
					  }
			
		}//catch card errors

		catch(Stripe_CardError $e)
			{
				$e_json = $e->getJsonBody();
				$err = $e_json['error'];
				$message = $err['message'];
			}//catch other exceptions
			catch (Exception $e)
				{
					trigger_error(print_r($e,1));
		 }
		 //End of stripe
	
			
		}//END of isset($order_id,$order_total)IF
		
		
  } //Errors occurred IF
} //End of REQUEST_METHOD IF

//include header
$page_title = $page_title = 'Billing | '.SITE_NAME.'';
include('./views/header_checkout.inc.html');
//grab shopping cart contents
//$r = mysqli_query($dbc, "CALL get_shopping_cart_contents('$uid')");
//if(!$r) echo mysqli_error($dbc);

//include view files
//if(mysqli_num_rows($r) >0)
	//{
		if(isset($_SESSION['shipping_for_billing']) && ($_SERVER['REQUEST_METHOD'] !=='POST'))
			{
				$values = 'SESSION';
			}else{
				$values = 'POST';
			}
			 include('./views/billing.inc.html');
			 //echo mt_rand(1000000, 10000000);
			 //echo 'id'.$_SESSION['customer_id'];
			 //echo '<br /> order'.$_SESSION['order_id'];
	// }else{//cart empty
	// include('./views/emptycart.html');
	//}
	 
include('./views/footer.inc.html');
?>