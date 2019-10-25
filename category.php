<?php
/********************************category*************************************/
include('includes/config.inc.php');


require(MYSQL);

$page_title ="Categories | Scrapinventory";
include('./views/header.inc.html');
//include('./views/index.inc.html');

if(filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' =>1)))
	{
		$cat_id = $_GET['id'];
		
		$q = "SELECT commodity FROM commodities WHERE commodity_id='.$cat_id.'";
		
		if(mysqli_num_rows($r) !==1)
			{
				$page_title = 'OOppss! Error';
				include('./views/header.inc.html');
				echo '<div class="alert alert-danger">OOppss! This page has been accessed in error.</div>';
				includes('./views/footer.inc.html');
				exit();
			}
			
		list($page_title)= mysqli_fetch_array($r, MYSQLI_NUM);
		include('./views/header.inc.html');
		echo '<h1>'. htmlspecialchars($page_title).'</h1>';
		
		if(isset($_SESSION['user_id']) && !isset($_SESSION['user_not_expired']))
			{
				echo '<div class="alert"><h4>Expired Account</h4>Thank you for your interest in this content.  Unfortunatley
				your account has expired. Please <a href="renew.php">renew your account</a> in order to access site content.</div>';
			}elseif(!isset($_SESSION['user_id']))
				{
					echo '<div class="alert">Thank you for your interest in this content. Yu must be logged in as a registered user
					to view site content.</div>';
				}
				
				$q = "SELECT commodity FROM commodities WHERE commodity_id='.$cat_id.'";
				
				$r= mysqli_query($dbc, $q);
				
				if(mysqli_num_rows($r) >0)
					{
						while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
							{
								//echo '<div><h4><a href="##"?id='.row['id'].'">'. htmlspecialchars($row['']).'</a></h4>,p>'. 
							//	htmlspecialchars($row['']).'</p></div>';
							}//END while loop
					}else{
						//no pages availble
						echo '<p>There is no content---type </p>';
					}
	}else{
		$page_title ='OOPPss! Error!';
		include('./views/header.inc.html');
		echo '<div class="alert alert-danger">OOPP! This page has been accesssed in error.</div>';
	}
includes('./views/fotter.inc.html');
