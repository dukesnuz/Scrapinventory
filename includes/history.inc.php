 <?php
               
               if(!isset($dbc))
               			{
               				exit();
               			}
               
               
                if(isset( $_SESSION['uid'])) 
                  {
                  	$uid = mysqli_real_escape_string($dbc, $_SESSION['uid']);
				  }else{
				  	$uid = Null;
				  }
				  
				 if(isset( $_SESSION['cid'])) 
                  {
                  	$cid = mysqli_real_escape_string($dbc, $_SESSION['cid']);
				  }else{
				  	$cid = Null;
				  }
				  
				 if(isset( $_GET['name'])) 
                  {
                  	$in = mysqli_real_escape_string($dbc, $_GET['name']);
				  }else{
				  	$in = Null;
				  }
				  
				 if(isset( $_GET['id'])) 
                  {
                  	$id = mysqli_real_escape_string($dbc, $_GET['id']);
				  }else{
				  	$id = Null;
				  }

				  if(isset($_SERVER['PHP_SELF'])) 
                  {
                  		 $p = basename($_SERVER['PHP_SELF']);
				  }else{
				    	$p = Null;
				  }
				  if(isset($_SERVER['REMOTE_ADDR']))
				  	{
				  		 $ip = $_SERVER['REMOTE_ADDR'];
				  	}else{
				  		$ip = Null;
				  	}

    $q= "INSERT INTO history (user_id,company_id,item_id,item_name, page_title, page,ip)
									VALUES('$uid','$cid','$id','$in','$page_title','$p', '$ip')";
						
						     $r = mysqli_query($dbc, $q);
							if(mysqli_affected_rows($dbc) !=1)
								{
									$body = "Error on SITE_NAME\n";
									$body .="Page: $page_title Line 50 history query\n";
									$body .="END email";
<<<<<<< HEAD
									mail(CONTACT_EMAIL_2,'Error'.SITE_NAME.'', $body, 'From:'.CONTACT_EMAIL.'');
=======
									mail(CONTACT_EMAIL,'Error'.SITE_NAME.'', $body, 'From:'.CONTACT_EMAIL.'');
>>>>>>> 10ab8b6629f9210eae104f200e890fe17b30716a
								}
	
