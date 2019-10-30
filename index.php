<?php
/**********************************This is the page format for each page.  I ma using the mvc format******************/
/**********************This page also usee****************************************************************************/
/***********---------------------------------*************************************************************************/

include('includes/config.inc.php');


require(MYSQL);

if ($_SERVER['REQUEST_METHOD'] ==='POST') {
    include('./includes/login.inc.php');
}
$page_title ="Home | ".SITE_NAME."";
include('./views/header.inc.html');

/*******************Grab number of page views for this company to display in renew*********/
if (isset($_SESSION['cid'])) {
    $cid = $_SESSION['cid'];

    $qh = "SELECT COUNT(item_id) AS history_count FROM history
	                 WHERE item_id = '$cid'
	                 AND
	                 page = 'company_detail.php'
	                 AND
	                 company_id != '$cid'
	                 ";
    $fh = mysqli_query($dbc, $qh);
    if (mysqli_num_rows($fh) === 1) {
        $rowh= mysqli_fetch_array($fh, MYSQLI_ASSOC);
        $ch = $rowh['history_count'];
    } else {
        $ch = 0;
    }
}

/*************************Grab news feeds***********************/
 $q = "SELECT title, description, url, tags,type FROM news
          WHERE status = 'true'
          ORDER BY date_created DESC
          LIMIT 20";

            $r = mysqli_query($dbc, $q);

/************************END grab news feeds********************/
include('./views/index.inc.html');


if (!isset($ip)) {
    $ip = 0;
}
/*
$body = "Visitor on:".SITE_NAME."\n";
$body .= "This email on index.php page\n";
$body .= "Line 23\n";
$body .= "Ip:".$ip."\n";
$body .= "END email";
mail(CONTACT_EMAIL_2,'Visitor on:'.SITE_NAME,$body, 'From:'.CONTACT_EMAIL);
*/
include('./views/footer.inc.html');
