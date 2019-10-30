<?php
/**********************************This is the page format for each page.  I ma using the mvc format******************/
/**********************This page also usee****************************************************************************/
/***********register_company.php*************************************************************************/
include('includes/config.inc.php');


require(MYSQL);

$page_title ='Register Company | '.SITE_NAME.'';
include('./views/header.inc.html');
//include('./views/index.inc.html');
//!isset($_SESSION['cid']) &&
if (!isset($_SESSION['uid']) && !isset($_SESSION['email'])) {
    echo '<div><h3 class="alert-danger">OOppss! This page accsessed in error!</h3>
		  <p class= "alert warning fade in">Have you registered as a user first?
		  <a href="/register-user/">Reister Here<a/>

	</div';
    //header("Location: index.php");
    include('./views/footer.inc.html');
    die();
}



/////////Set variables/////////
    $email = $_SESSION['email'];
    $uid = $_SESSION['uid'];
    //$cid = $_SESSION['cid'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $reg_errors = array();

//escape_data($_POST['company'], $dbc); Not working
//using
//$fn = mysqli_real_escape_string($dbc, $_POST['first_name']);
//check for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (preg_match('/^[A-Z \'.-]{2,30}$/i', $_POST['company'])) {
        //$company = escape_data($_POST['company'], $dbc);
        $company= mysqli_real_escape_string($dbc, $_POST['company']);
    } else {
        $reg_errors['company'] = 'Please enter your company name.';
    }

    if (preg_match('/^[A-Z0-9 \',.#-]{2,160}$/i', $_POST['c_address'])) {
        //$c_address = escape_data($_POST['c_address'], $dbc);
        $c_address = mysqli_real_escape_string($dbc, $_POST['c_address']);
    } else {
        $reg_errors['c_address'] = 'Please enter your company address.';
    }

    if (preg_match('/^[A-Z \'.-]{2,60}$/i', $_POST['c_city'])) {
        //$c_city = escape_data($_POST['c_city'], $dbc);
        $c_city = mysqli_real_escape_string($dbc, $_POST['c_city']);
    } else {
        $reg_errors['c_city'] = 'Please enter your company city.';
    }

    if (preg_match('/^[A-Z]{2}$/', $_POST['c_state'])) {
        //$c_state = escape_data($_POST['c_state'], $dbc);
        $c_state = mysqli_real_escape_string($dbc, $_POST['c_state']);
    } else {
        $reg_errors['c_state'] = 'Please enter your company state';
    }

    if (preg_match('/^(\d{5}$)|(^\d{5}-\d{4})$/', $_POST['c_zip'])) {
        //$c_zip = escape_data($_POST['c_zip'], $dbc);
        $c_zip = mysqli_real_escape_string($dbc, $_POST['c_zip']);
    } else {
        $reg_errors['c_zip'] = 'Please enter your company zip code';
    }

    if (preg_match('/^[A-Z \'.-]{2,20}$/i', $_POST['c_country'])) {
        //$c_country = escape($_POST['c_country'], $dbc);
        $c_country = mysqli_real_escape_string($dbc, $_POST['c_country']);
    } else {
        $reg_errors['c_country'] = 'Please enter your company country';
    }


    //telepnone and fax
    // $p = str_replace(array('','-', '(',')'),'', $p);
    $c_telephone =str_replace(array(' ','-', '(',')'), '', $_POST['c_telephone']);
    if (preg_match('/^[0-9]{10}$/i', $c_telephone)) {
        //$c_telephone = escape_data($c_telephone, $dbc);
        $c_telephone = mysqli_real_escape_string($dbc, $c_telephone);
    } else {
        $reg_errors['c_telephone'] = 'Please enter your company telephone number';
    }

    $c_fax =str_replace(array(' ','-', '(',')'), '', $_POST['c_fax']);
    if (preg_match('/^[0-9]{9,10}$/i', $c_fax)) {
        //$c_fax = escape_data($c_fax, $dbc);
        $c_fax = mysqli_real_escape_string($dbc, $c_fax);
    } else {
        $reg_errors['c_fax'] = 'Please enter your company fax number';
    }

    // if(preg_match('/^[A-Z \'.-]{2,15}$/i', $_POST['c_type_company']))
    if (preg_match('/^[0-9]{1,4}$/i', $_POST['c_type_company'])) {
        //$c_type_company = escape_data($_POST['c_type_company'], $dbc);
        $c_type_company_id= mysqli_real_escape_string($dbc, $_POST['c_type_company']);
    } else {
        $reg_errors['c_type_company'] = 'Please enter your company type.';
    }
    /*
     if(preg_match('/^[A-Z]{2,13}$/', $_POST['c_type_company_other']))
        {
            //$c_type_company = escape_data($_POST['c_type_company'], $dbc);
            $c_type_company_other = mysqli_real_escape_string($dbc, $_POST['c_type_company_other']);
        }else{
            $reg_errors['c_type_company_other'] = 'Please enter your other type company.';
        }
        */
    //if(preg_match('/^[A-Z \'.-]{2,15}$/i', $_POST['c_commodity']))
    if (preg_match('/^[0-9]{1,4}$/i', $_POST['c_commodity'])) {
        //$c_commodity= escape_data($_POST['c_commodity'], $dbc);
        $c_commodity_id = mysqli_real_escape_string($dbc, $_POST['c_commodity']);
    } else {
        $reg_errors['c_commodity_id'] = 'Please enter your commodity type.';
    }
    /*
        if(preg_match('/^[A-Z \'.-]{2,15}$/i', $_POST['c_commodity_other']))
        {
             //$c_commodity= escape_data($_POST['c_commodity'], $dbc);
             $c_commodity_other = mysqli_real_escape_string($dbc, $_POST['c_commodity_other']);
            }else{
            $reg_errors['c_commodity_other'] = 'Please enter your other commodity type.';
        }
        */

    if (preg_match('/^[A-Z 0-9 \',.-]{5,100}$/i', $_POST['c_website'])) {
        //$c_website = escape_data($_POST['c_website'], $dbc);
        $c_website = mysqli_real_escape_string($dbc, $_POST['c_website']);
    } else {
        $reg_errors['c_website'] = 'Please enter about your company website.';
    }

    if (preg_match('/^[A-Z 0-9 \',.-]{2,500}$/i', $_POST['c_about'])) {
        //$c_about = escape_data($_POST['c_about'], $dbc);
        $c_about = mysqli_real_escape_string($dbc, $_POST['c_about']);
    } else {
        $reg_errors['c_about'] = 'Please enter about your company.';
    }





    //echo 55;
    //if no errors cheack to see if user registered a company already be checking company_id in user table
    if (empty($reg_errors)) {
        ///get cid from users
        $uid = $_SESSION['uid'];
        $q = "SELECT company_id FROM users
	 	              WHERE user_id= '$uid' ";
        $r = mysqli_query($dbc, $q);

        if (mysqli_affected_rows($dbc) ===1) {
            $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
            $cid = $row['company_id'];
        } else {
            trigger_error('You could not be registered due to a system error.
			                  We apologize for any inconvience. We will correct the error2.');
        }





        $q = "SELECT company_id FROM companies
	 	          WHERE company_id= '$cid' ";
        $r = mysqli_query($dbc, $q);
        $rows = mysqli_num_rows($r);
        // echo 77;
        if ($rows === 0) {
            //if(4>6)
        //echo 1;
            //originally use minus one day  SUBDATE(NOW(), INTERVAL 1 DAY)
            //add 5 years
            //INSERT INTO companies(`date_expires`) VALUES(DATE_ADD(NOW(), INTERVAL 20 year))
            //$sql = "INSERT INTO companies(`date_expires`) VALUES(DATE_ADD(NOW(), INTERVAL 20 year))"; //DATE_ADD(curdate,INTERVAL 10 year)
            $q = "INSERT INTO companies (company, address, city, state, zip, phone, fax, country,type,ip,web_site,about, user_id, company_id,date_expires)
	        VALUES( '$company','$c_address','$c_city','$c_state','$c_zip','$c_telephone','$c_fax','$c_country',0,'$ip','$c_website','$c_about','$uid','$cid', NOW() + INTERVAL 10 year)";

            $r = mysqli_query($dbc, $q);

            if (mysqli_affected_rows($dbc) === 1) {
                $q = "INSERT INTO commodity_company(commodity_id, company_id)
						VALUES( '$c_commodity_id', '$cid')";
                $r = mysqli_query($dbc, $q);
            }

            if (mysqli_affected_rows($dbc) === 1) {
                $q = "INSERT INTO companytype_company(companytype_id, company_id)
						VALUES( '$c_type_company_id', '$cid')";
                $r = mysqli_query($dbc, $q);
            }


            //query created one row, thnak new customer and send an email
            if (mysqli_affected_rows($dbc) === 1) {
                echo '<div class="alert alert-success"><h3>Success</h3>
							<p>Thank you for registering.</p>
							<P>We hope you enjoy our website.</p>';
                echo  "<p>Welcome committee sent an email to: $email.</p></div>";


                //$body = "".$_SESSION[username]."\n\n";
                $body = "$company\n\r";
                $body .= "Thank you for registering your company at ".SITE_NAME.".\n\n";
                $body .= "We hope you enjoy our website.\n\n";
                $body .="Follow us on twitter @".SITE_NAME."\n";
                $body .= "http://twitter.com/".SITE_NAME."\n";
                $body .="Thank you\n";
                $body .="The team at ".BASE_URL."\n";
                $body .="END Email";

                mail($email, 'Company Registration Recieved at '.SITE_NAME.'', $body, 'From:'.CONTACT_EMAIL);

                //email me
                $body1  ="Company regigistered a company on ".SITE_NAME.".\n";
                $body1 .= "User email: $email\n";
                $body1 .= "Company: $company\n\n";
                $body1 .="Email to register\n$body\n\n";
                $body1 .="END Email";

                mail(CONTACT_EMAIL_2, 'Registered Co. on '.SITE_NAME.'', $body1, 'From:'.CONTACT_EMAIL);



                /**************Set session for cid*********************/
                $_SESSION['cid'] = $cid;


                include('./views/footer.inc.html');
                exit();

            //if query did not work create error
            } else {
                trigger_error('You could not be registered due to a system error.
			            We apologize for any inconvience. We will correct the error.');
            }
        } else {
            //if email address or username is unavailable, create errors
            //echo 88;
            if ($rows ===1) {
                //if(4>2);
                echo '<h4 class="alert alert-danger">You have already registered a company with this email address.';
                include('./views/footer.inc.html');
                die();
                //$reg_errors['email'] = 'You have already registered a company with this email address.';
               // $reg_errors['username'] = 'this username has already been registered. Please try another.';
            }
            //}else{
            //confirm which item has been registered
            /*$row= mysqli_fetch_array($r, MYSQL_NUM);
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
                }	*/
        // }//end of $rows == 2 else
        } //end of $rows ===0 IF
    } //end of empty($reg_errors) IF
}//end if($_SERVER['REQUEST_METHOD']==="POST")



require_once('./includes/form_functions.inc.php');
include('./views/register_company.inc.html');
include('./views/footer.inc.html');
