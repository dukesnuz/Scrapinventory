<?php
/**********************************Favorites*************************************/


include('includes/config.inc.php');
require(MYSQL);

//$page_title ="Categories | Scrapinventory";
//include('./views/header.inc.html');
//include('./views/index.inc.html');
//_SESSION['uid'] = 1;
//$_SESSION['cid'] = 1;
//$_SESSION['username'] = 'username';
//$_SESSION['user_not_expired'] = true;
//144707177 
if(isset($_SESSION['cid']) && isset($_SESSION['uid']))
//if(4>6)
	{
		
		$page_title ="Favorites Saved | ".SITE_NAME."";
        include('./views/header.inc.html');
				
		$uid = $_SESSION['uid'];		
 $q = "SELECT c.company,c.company_id, b.companytype FROM favorite_pages AS f
 		 INNER JOIN companies AS c
 		 INNER JOIN companytype_company AS t
 		 INNER join company_type AS b
         WHERE 
         t.company_id = f.page_id
         AND
         c.company_id = f.page_id
		 AND
         f.user_id ='$uid' 
         AND 
         b.companytype_id = t.companytype_id
         AND
         f.active = 'true'
         order by c.company ASC
         "; 
		 	

$r = mysqli_query($dbc,$q);

				 

if(mysqli_num_rows($r) > 0)
   {
   	      
				include('./views/favorites.inc.html');
		
	}else{
	
		        echo '<h4 class="alert alert-danger">You do not have any saved favorite companies.</h4>';
	}



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
