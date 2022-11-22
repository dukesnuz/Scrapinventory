<?php
/*****************company detail********************/
include('includes/config.inc.php');


require(MYSQL);


//also add if subscriber paid
//$_SESSION['uid'] = 1;
//$_SESSION['cid'] = 177913324;
//$_SESSION['username'] = 'username';
//$_SESSION['user_not_expired'] = true;
//$uid = $_SESSION['uid'];
//$_GET['name']= "";



if(filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' =>1)) && isset($_SESSION['cid']) && isset($_SESSION['uid']))
//if(4>2)
	{
		$id = $_GET['id'];
        $cid = $_SESSION['cid'];
		$uid = $_SESSION['uid'];
	//$q = "SELECT company,address,city,state,zip,phone,fax,country,about,web_site,active FROM companies 
	//phone/fax add - or ()
	//http://forums.mysql.com/read.php?10,280544,280555
	
	$q = "SELECT company,address,city,state,zip,
	        concat('(',mid(phone,1,3),') ',mid(phone,4,3),'-',mid(phone,7,4)) as phone,
	        concat('(',mid(phone,1,3),') ',mid(phone,4,3),'-',mid(phone,7,4)) as fax,
			country,about,web_site,active,IF(date_expires >= NOW(), true,false) as expired
			FROM companies
			Where
			company_id ='$id'
			AND
			active=true ";
			
			
		  $r = mysqli_query($dbc, $q);
		  if(mysqli_num_rows($r) != "1")
			{
				$page_title = 'OOppss! System Error | '.SITE_NAME.'';
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
			

				$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
			
			/*************************grab favorites*************************/
			$pid = $_GET['id'];
			//$uid = 1;
			$fq= "SELECT user_id From favorite_pages 
					WHERE 
					user_id= '$uid'
					AND
					page_id = '$pid'
					AND 
					active ='true'";
			  // $fr =mysqli_query($dbc, $q);
			     $fr = mysqli_query($dbc, $fq);
			 // $favorites = mysqli_fetch_array($fr,MYSQLI_ASSOC);
		/*************************END grab favorites**************************/
		/********************************grab page views and saved count******/
							
	$qf = "SELECT COUNT(page_id) AS favorite_count FROM favorite_pages AS f 
	                                    WHERE page_id = '$pid'
	                                    AND
	                                    active = 'true' ";
						$ff = mysqli_query($dbc, $qf);
						if(mysqli_num_rows($ff) === 1)
							{
								$rowf = mysqli_fetch_array($ff,MYSQLI_ASSOC);
								$cu ='Saved as Favorites: '.$rowf['favorite_count'];
							}else{
								$cu = 0;
							}

					
	$qh = "SELECT COUNT(item_id) AS history_count FROM history
	                 WHERE item_id = '$pid'
	                 AND
	                 page = 'company_detail.php'
	                 AND
	                 company_id != '$pid'
	                 ";
/*
 		$qh = "SELECT COUNT(item_id) AS history_count FROM history
                     WHERE item_id = '$cid'
	                 AND
	                 page = 'company_detail.php'
	                 AND
	                 company_id != '$pid'";
*/					 
					$fh = mysqli_query($dbc, $qh);
						if(mysqli_num_rows($fh) === 1)
							{
								$rowh= mysqli_fetch_array($fh,MYSQLI_ASSOC);
								$ch = 'Page Views: '.$rowh['history_count'];
							}else{
								$ch = 0;
							}
									
		/******************************END page views and saved count*********/
			//$page_title = 'Details for: '.$_GET['name'] . ' | '.SITE_NAME.'';
			$page_title = 'Details for: '.$row['company'] . ' | '.SITE_NAME.'';
			include('./views/header.inc.html');
/*************************************Check if useer subscribed*******************************************/
       //isset($_SESSION['uid']) && isset($_SESSION['cid']) && 
    	/*if(!isset($_SESSION['uid']))
		     {
				   echo '<div class="alert">Thank you for your interest in this content. You must be logged in as a registered user
					to view site content.</div>';
					
				}elseif(!isset($_SESSION['user_not_expired']) )
			  */
			  if(!isset($_SESSION['user_not_expired']) )
				{
					echo '<div class="alert"><h4>Expired Account</h4>Thank you for your interest in this content.  Unfortunatley
				your account has expired. Please <a href="'.BILLING_URL.'">update your account</a> in order to access site content
				and allow other viewers to view your company information.
				<br />Current <strong>'.$ch.'</strong>.</div>';
				}else{
/******************************END check if user subscribed************************************************/
					/******check if company is still subscribed*******/
					 if($row['expired'] ==='1' ) 
						{
			             include('./views/company_detail.inc.html');
						}else{
							echo 1111;
							print_r($row);
							echo $_GET['id'];
							echo $row['companies'] ;
							echo $row['date_expires'];
							echo $row['expired'] ;
						
							echo '<div class="alert alert-info">This company\'s information is no longer avaialble on our site.</div>';
						}
				}
				
		
			   include('./views/footer.inc.html');
			   exit();
	}else{
		$page_title ='OOPPss! Please log in | '.SITE_NAME.'';
		include('./views/header.inc.html');
		    	echo '<div class="alert">Thank you for your interest in this content. You must be logged in as a registered user
					to view site content.</div>';
		include('./views/footer.inc.html');
		
	}
	
	
	
