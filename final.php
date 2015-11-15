<?php
//final.php
require('./includes/config.inc.php');

//session_start();
//$uid = session_id();

//validate page being accessed correctly
//$_SESSION['oid'] = 1;
//$_SESSION['otl'] = 1;
//$_SESSION['cid'] = 1;

if(!isset($_SESSION['cid']) && !isset($_SESSION['oid']))
	{
		$location = './index.php';
		header("Location: $location");
		exit();
	}
	

//chp 13 includes email_receipt.php
//include('./includes/email_receipt.php');


$page_title = 'Order Complete | '.SITE_NAME.'';
include('./views/header.inc.html');
include('./views/final.inc.html');
//clear session
 $_SESSION = array();
 session_destroy();



include('./views/footer.inc.html');
?>