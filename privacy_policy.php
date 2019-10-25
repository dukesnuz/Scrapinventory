<?php
/**********************************This is the page format for each page.  I ma using the mvc format******************/
/**********************This page also usee****************************************************************************/
/***********-privacy-policy.php*************************************************************************/

include('includes/config.inc.php');


require(MYSQL);

if($_SERVER['REQUEST_METHOD'] ==='POST')
	{
		include('./includes/login.inc.php');
	}
$page_title ='Privacy Policy | '.SITE_NAME.' ';
include('./views/header.inc.html');
include('./views/privacy_policy.inc.html');
include('./views/footer.inc.html');
