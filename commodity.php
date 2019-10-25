<?php
/*****************commodity*****************PAGE 125 similiar to category.php********************/
include('includes/config.inc.php');

//$_SESSION['user_not_expired'] = false;

require(MYSQL);

//$page_title ="Categories | Scrapinventory";
//include('./views/header.inc.html');
//include('./views/index.inc.html');
//also add if subscriber paid
//$_SESSION['uid'] = 1;
//$_SESSION['cid'] = 1;
//$_SESSION['username'] = 'username';
//$_SESSION['user_not_expired'] = true;


if(filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' =>1)) && isset($_SESSION['uid']) )
//if(4>2)
	{
		$cat_id = $_GET['id'];
		
		$q = "SELECT commodity FROM commodities WHERE commodity_id=$cat_id ";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) != "1")
		//if(mysqli_num_rows($r) >0)
		//if(mysqli_num_rows($r) >1)
		
			{
				$page_title = 'OOppss! Error | '.SITE_NAME.'';
				include('./views/header.inc.html');
				echo '<div class="alert alert-danger">OOppss! This page has been accessed in error.1</div>';
				include('./views/footer.inc.html');
				exit();
			}
			
		//list($commodity)= mysqli_fetch_array($r, MYSQLI_NUM);
		//$row = mysqli_fetch_array($r, MYSQLI_NUM);
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		$page_title =  $row['commodity'] .' | '.SITE_NAME.'';
		include('./views/header.inc.html');
		//echo '<h1>Companies that specialize in '. htmlspecialchars($row['commodity']).'</h1>';
		//echo $row['commodity'];
		//echo $cat_id;
		if(!isset($_SESSION['cid']))
			{
				        echo '<div class="alert">Thank you for your interest in this content. You must register your company to
							view details of companies listed on our site.</div>';
					
			}elseif(!isset($_SESSION['user_not_expired'] ))
				{
						echo '<div class="alert"><h4>Expired Account</h4>Thank you for your interest in this content.  Unfortunatley
			      	your account has expired. Please <a href="'.BILLING_URL.'">update your account</a> in order to access site content.</div>';
				}
				
				
				  $q = "SELECT b.company_id, c.company FROM commodity_company AS b
						INNER JOIN companies AS c
						WHERE 
						b.company_id = c.company_id
						AND
						b.commodity_id='$cat_id'
						AND
						c.active='true' ";
						
				//$q = "SELECT * FROM companytype_company WHERE companytype_id='$cat_id' ";
				
				$r= mysqli_query($dbc, $q);
				
		      	include('./views/commodity.inc.html');

	}else{
		$page_title ='OOPPss! Please log in | '.SITE_NAME.'';
		include('./views/header.inc.html');
			 if(!isset($_SESSION['uid']))
		  	   {
			      echo '<div class="alert">Thank you for your interest in this content. You must be logged in as a registered user
					to view site content.</div>';
				}else{
						echo '<div class="alert alert-danger">OOppss! This page has been accessed in error.</div>';
				}
	}
include('./views/footer.inc.html');
