<?php
/************************logout**************************************/
require('./includes/config.inc.php');
redirect_invalid_user();
$_SESSION = array();
session_destroy();
setcookie(session_name(), '',time()-300);
require(MYSQL);
$page_title = "Logout |scrapinventory";
include('./views/header.inc.html');
echo '<h1>Logged Out</h1><p>Thank you for visiting. You are now logged out. Please come back soon!';
include('./views/footer.inc.html');
?>
