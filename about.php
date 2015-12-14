<?php
/**********************************This is the page format for each page.  I ma using the mvc format******************/
/**********************This page also usee****************************************************************************/
/***********---------------------------------*************************************************************************/

include('includes/config.inc.php');

//also add if subscriber paid
//$_SESSION['uid'] = 1;
//$_SESSION['cid'] = 177913324;
//$_SESSION['username'] = 'username';
//$_SESSION['user_not_expired'] = true;
//$uid = $_SESSION['uid'];

require(MYSQL);

if($_SERVER['REQUEST_METHOD'] ==='POST')
	{
		include('./includes/login.inc.php');
	}
$page_title ='About |  '.SITE_NAME.'';
include('./views/header.inc.html');
/*******************Grab number of page views for this company to display in renew*********/
If(isset($_SESSION['cid']))
	{
		 $cid = $_SESSION['cid'];
		 
	       $qh = "SELECT COUNT(item_id) AS history_count FROM history
	                 WHERE item_id = '$cid'
	                 AND
	                 page = 'company_detail.php'
	                 AND
	                 company_id != '$cid'
	                 ";
						$fh = mysqli_query($dbc, $qh);
						if(mysqli_num_rows($fh) === 1)
							{
								$rowh= mysqli_fetch_array($fh,MYSQLI_ASSOC);
								$ch = $rowh['history_count'];
							}else{
								$ch = 0;
							}
	}	
include('./views/about.inc.html');
include('./views/footer.inc.html');
