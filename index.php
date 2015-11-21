<?php
/**********************************This is the page format for each page.  I ma using the mvc format******************/
/**********************This page also usee****************************************************************************/
/***********---------------------------------*************************************************************************/

include('includes/config.inc.php');


require(MYSQL);

if($_SERVER['REQUEST_METHOD'] ==='POST')
	{
		include('./includes/login.inc.php');
	}
$page_title ="Home | ".SITE_NAME."";
include('./views/header.inc.html');
include('./views/index.inc.html');
include('./views/footer.inc.html');

/*
if(!isset($ip))
	{
		$ip = 0;
	}
$body = "Visitor on:".SITE_NAME."\n";
$body .= "This email on index.php page\n";
$body .= "Line 23\n";
$body .= "Ip:".$ip."\n";
$body .= "END email";
mail(CONTACT_EMAIL_2,'Visitor on:'.SITE_NAME,$body, 'From:'.CONTACT_EMAIL);
*/