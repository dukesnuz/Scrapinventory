<?php
//page 3
//includes/form_functions.inc.php
/******************************Below from part 2************************************************/

//form_functions.inc.php

/***************************************Below from part 1*/
function create_form_input($name, $type, $label = '', $errors = array(), $options = array()) {
	
	// Assume no value already exists:
	$value = false;
	
	// Check for a value in POST:
	if (isset($_POST[$name]))
	{
		 $value = $_POST[$name];
	}
	// Strip slashes if Magic Quotes is enabled:
	if ($value && get_magic_quotes_gpc()) 
	{
		$value = stripslashes($value);
	}
	// Start the DIV:
	echo '<div class="form-group';

	// Add a class if an error exists:
	if (array_key_exists($name, $errors)) 
	{
		echo ' has-error';
	}
	// Complete the DIV:
	echo '">';

	// Create the LABEL, if one was provided:
	if (!empty($label)) 
	{
		echo '<label for="' . $name . '" class="control-label">' . $label . '</label>';
	}
	// Conditional to determine what kind of element to create:
	if ( ($type === 'text') || ($type === 'password') || ($type === 'email')) {
		
		// Start creating the input:
		echo '<input type="' . $type . '" name="' . $name . '" id="' . $name . '" class="form-control"';
		
		// Add the value to the input:
		if ($value) echo ' value="' . htmlspecialchars($value) . '"';
		
		// Check for additional options:
		if (!empty($options) && is_array($options)) {
			foreach ($options as $k => $v) {
				echo " $k=\"$v\"";
			}
		}
		
		// Complete the element:
		echo '>';
		/****************start select***************************/
		       //check for select type
	}elseif($type === 'select')
		{
			//if atates created define data source
			if(($name ==='c_state') || ($name === 'cc_state') )
			{
					$data = array('AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming');
              }elseif($name === 'cc_exp_month')
			{
					$data = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',  'September', 'October', 'November', 'December');
			}elseif($name === 'cc_exp_year')
			{  //if expiration month menu define data source
				$data= array();
				$start = date('Y');
				for ($i = $start; $i <= $start +5; $i++)
				{
					$data[$i] = $i;
				}
				
		//	}	// end of $name IF-ELSEIF
			}elseif($name === 'c_type_company')
				{
					//grab catagories
				  //require(MYSQL_2);
				 //mysqli_close($dbc);
				//  include('./include_2/mysql_connect.php');
				  DEFINE('DB_USER1','scrapinventory');
                  DEFINE('DB_PASSWORD1', 'Stacy1964!');
                  DEFINE('DB_HOST1', 'localhost');
                  DEFINE('DB_NAME1', 'scrapinventory');

                   $dbc1 = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                
                    $q = 'SELECT * FROM company_type ORDER BY companytype';
		            $r = mysqli_query($dbc1, $q);
				
						if(mysqli_num_rows($r) >1)
							{
				
					      echo '<select name= "'.$name .'"';
		  //add error array
		 if(array_key_exists($name,$errors))
		    {
		    	 echo ' class="error"';
			}
		  echo ' class="form-control" >';
		  
		  //create each option
		   while(list($id,$data) =  mysqli_fetch_array($r, MYSQLI_NUM))
					{
		  				echo "<option value=\"$data\"";
						//if($value === $k) echo ' selected="selected"';
						echo ">$data</option>\n";
		  			}
	
//complete tag
			echo '</select>';
			
							}
		//////////////////////////////////////////////
		}elseif($name === 'c_commodity')
				{
					//grab catagories
				  //require(MYSQL_2);
				 //mysqli_close($dbc);
				//  include('./include_2/mysql_connect.php');
				  DEFINE('DB_USER2','scrapinventory');
                  DEFINE('DB_PASSWORD2', 'Stacy1964!');
                  DEFINE('DB_HOST2', 'localhost');
                  DEFINE('DB_NAME2', 'scrapinventory');

                   $dbc2 = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
				   
                    $q = 'SELECT * FROM commodity ORDER BY commodity';
		            $r = mysqli_query($dbc2, $q);
				
						if(mysqli_num_rows($r) >1)
							{
				
					      echo '<select name= "'.$name .'"';
		  //add error array
		  if(array_key_exists($name,$errors))
		    {
		    	 echo ' class="error"';
			}
		  		echo ' class="form-control" >';
		  
		  			//create each option
		   		while(list($id,$data) =  mysqli_fetch_array($r, MYSQLI_NUM))
					{
		  				echo "<option value=\"$data\"";
						//if($value === $k) echo ' selected="selected"';
						echo ">$data</option>\n";
		  	}
	
//complete tag
			echo '</select>';
			
							}
		//////////////////////////////////////////////
							}
	
//Below is for select for states aas they are not stored in database	
if(($name ==='c_state') || ($name === 'cc_state') )
			{		
			
          //create openeinf select tag
          echo '<select name= "'.$name .'"';
		  //add error array
		  if(array_key_exists($name,$errors))
		    {
		    	 echo ' class="error"';
			}
		          echo ' class="form-control" >';
		  
		 				 //create each option
		 				 foreach ($data as $k => $v)
		  					{
		  						echo "<option value=\"$k\"";
									if($value === $k) echo ' selected="selected"';
										echo ">$v</option>\n";
		  								}
	
							//complete tag
			echo '</select>';
			/*****************END select ****************/

			
			
//END companytype


}//END IF $name
			
			
			
			
			
			
		// Show the error message, if one exists:
		if (array_key_exists($name, $errors)) 
		{
			echo '<span class="help-block">' . $errors[$name] . '</span>';
		}
	      } elseif ($type === 'textarea') { // Create a TEXTAREA.
		
		// Show the error message above the textarea (if one exists):
		if (array_key_exists($name, $errors)) 
		{
			echo '<span class="help-block">' . $errors[$name] . '</span>';
		}
		// Start creating the textarea:
		echo '<textarea name="' . $name . '" id="' . $name . '" class="form-control"';
		
		// Check for additional options:
		if (!empty($options) && is_array($options)) {
			foreach ($options as $k => $v) {
				echo " $k=\"$v\"";
			}
		}

		// Complete the opening tag:
		echo '>';		
		
		// Add the value to the textarea:
		if ($value) 
		{
			echo $value;
		}
		// Complete the textarea:
		echo '</textarea>';
		
	} // End of primary IF-ELSE.
	
	// Complete the DIV:
	echo '</div>';

} // End of the create_form_input() function.

// Omit the closing PHP tag to avoid 'headers already sent' errors!