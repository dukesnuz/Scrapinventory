<?php
/***********favorites.php page also uses favorites.js***********************************/

include('../includes/config.inc.php');

//include('../include_2/mysql_connect.php');
//$_SESSION['uid'] = 144707177;
//$_SESSION['cid'] = 177913324;
       
		
		
if(isset($_GET['pid'], $_GET['action'], $_SESSION['uid']) 
		&& filter_var($_SESSION['uid'], FILTER_VALIDATE_INT, array('min_range' =>1))
		&& filter_var($_GET['pid'], FILTER_VALIDATE_INT, array('min_range' =>1))  )

	{
		require(MYSQL_2);
		$uid = $_SESSION['uid'];
		$pid = $_GET['pid'];
			
		if($_GET['action'] ==='add')
			{
				$active = 'true';
				//$uid = 1;
				//$pid = 33;
				$q = "INSERT INTO favorite_pages (active,user_id,page_id)	
								                   VALUES('$active', '$uid', '$pid')";
								
			}elseif($_GET['action'] === 'remove')
			{
				$active = 'false';
				$q = "UPDATE favorite_pages set active = '$active'
								WHERE
								user_id = '$uid'
								AND
								page_id = '$pid'
								AND
								active = 'true' ";

			}
			
			if(isset($q))
				{
					
					//echo $q;
					
					//$page_id = $_GET['pid'];
					//$page_id = 99;
					//$uid = "1";
					//$stmt = mysqli_prepare($dbc, $q);
					//mysqli_stmt_bind_param($stmt, 'sii',  $active, $_SESSION['uid'], $page_id);
					//mysqli_stmt_bind_param($stmt, 's',  $active);
					//mysqli_stmt_bind_param($stmt, 'ii',   $page_id, $uid);
					//mysqli_stmt_execute($stmt);
			  	   //if(mysqli_stmt_affected_rows($stmt) > 0)
			  	   
			  	   /**********************
				    $q = 'UPDATE users SET pass=? 
							WHERE user_id = ?
							LIMIT 1';
							
							$stmt = mysqli_prepare($dbc, $q);
							mysqli_stmt_bind_param($stmt, 'si', $pass, $_SESSION['uid']);
							$pass = password_hash($p, PASSWORD_BCRYPT);
							mysqli_stmt_execute($stmt);
							
							
							if(mysqli_stmt_affected_rows($stmt) === 1)
								{
				    */
		 			$r = mysqli_query($dbc, $q);
					//echo 'r======='.$r;
		            //query created one row, thnak new customer and send an email
		            if(mysqli_affected_rows($dbc) === 1 )
						{
							echo 'true';
							exit;
						}
				}
	}//invalid values or did not work
	echo 'false';
