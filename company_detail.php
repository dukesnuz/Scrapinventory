<?php
/*****************company detail********************/
include('includes/config.inc.php');


require(MYSQL);


//also add if subscriber paid
//$_SESSION['uid'] = 1;
//$_SESSION['cid'] = 1;

if(filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' =>1)) && isset($_SESSION['cid']) && isset($_SESSION['uid']))
//if(4>2)
	{
		$id = $_GET['id'];

	//$q = "SELECT company,address,city,state,zip,phone,fax,country,about,web_site,active FROM companies 
	//phone/fax add - or ()
	//http://forums.mysql.com/read.php?10,280544,280555
	$q = "SELECT company,address,city,state,zip,
	concat('(',mid(phone,1,3),') ',mid(phone,4,3),'-',mid(phone,7,4)) as phone,
	concat('(',mid(phone,1,3),') ',mid(phone,4,3),'-',mid(phone,7,4)) as fax,
			country,about,web_site,active FROM companies
			Where
			company_id =$id
			AND
			active=true";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) != "1")
			{
				$page_title = 'OOppss! Error';
				include('./views/header.inc.html');
				echo '<div class="alert alert-danger">OOppss! System error. We apologize for the inconvenience.</div>';
				include('./views/footer.inc.html');
				exit();
			}
		/***********************Grab commodity types**********************/
		    $qq = "SELECT c.commodity FROM commodities AS c
						INNER JOIN commodity_company AS b
						
						WHERE 
						b.commodity_id = c.commodity_id
						AND
						
						b.company_id='$id'
						";
						
				
				$rr= mysqli_query($dbc, $qq);
				
				/**********Grab company types*************/
			   $qqq = "SELECT c.companytype FROM company_type AS c
						INNER JOIN companytype_company AS b
						
						WHERE 
						
						c.companytype_id = b.companytype_id
						AND
						b.company_id='$id'";
						
				
				$rrr= mysqli_query($dbc, $qqq);
				
		
			/*********************END get commodity for this company***************/	
			$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
			$page_title = $_GET['name'] . ' | Scrapinventory';
			include('./views/header.inc.html');
/*************************************Check if useer subscribed*******************************************/
    	if(isset($_SESSION['uid']) && isset($_SESSION['cid']) && !isset($_SESSION['user_not_expired']))
		     	{
				echo '<div class="alert"><h4>Expired Account</h4>Thank you for your interest in this content.  Unfortunatley
				your account has expired. Please <a href="billing.php">update your account</a> in order to access site content.</div>';
			    }elseif(!isset($_SESSION['uid']))
				{
					echo '<div class="alert">Thank you for your interest in this content. You must be logged in as a registered user
					to view site content.</div>';
				}else{
/******************************END check if user subscribed************************************************/
			        include('./views/company_detail.inc.html');
				}
			   include('./views/footer.inc.html');
	}else{
		$page_title =' Company Detail | Scrapinventory';
		include('./views/header.inc.html');
		    	echo '<div class="alert">Thank you for your interest in this content. You must be logged in as a registered user
					to view site content.</div>';
		include('./views/footer.inc.html');
		
	}
	
	
	

