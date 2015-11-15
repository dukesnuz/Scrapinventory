<?php

//form_functions.inc.php

//define the function
function create_form_billing_input($name, $type, $errors = array(), $values = 'POST', $options = array() )
	{
		//look for and process any existing value
		$value = false;
		if($values === 'SESSION')
			{
			   if (isset($_SESSION[$name])) $value = htmlspecialchars($_SESSION[$name], ENT_QUOTES, 'UTF-8');
			   	}elseif($values === 'POST')
			{
				if (isset($_POST[$name])) $value = htmlspecialchars($_POST[$name], ENT_QUOTES, 'UTF-8');
				if ($value && get_magic_quotes_gpc()) $value = stripslashes($value);
	        }

//determine type of element
	// Conditional to determine what kind of element to create:
	if ( ($type === 'text') || ($type === 'password') ) { // Create text or password inputs.
		
		// Start creating the input:
		echo '<input type="' . $type . '" name="' . $name . '" id="' . $name . '"';

		// Add the value to the input:
		if ($value) echo ' value="' . $value . '"';

		// Check for any extras:
		if (!empty($options) && is_array($options)) {
			foreach ($options as $k => $v) {
				echo " $k=\"$v\"";
			}
		}
			
			if (array_key_exists($name, $errors)) {
			echo 'class="error" /> <span class="error">' . $errors[$name] . '</span>';
		} else {
			echo ' />';		
		}
      
       //check for select type
	}elseif($type === 'select')
		{
			//if atates created define data source
			if(($name ==='state') || ($name === 'cc_state'))
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
			}	// end of $name IF-ELSEIF
			
          //create openeinf select tag
          echo '<select name= "'.$name .'"';
		  //add error array
		  if(array_key_exists($name,$errors))
		    {
		    	 echo ' class="error"';
			}
		  echo '>';
		  
		  //create each option
		  foreach ($data as $k => $v)
		  	{
		  		echo "<option value=\"$k\"";
				if($value === $k) echo ' selected="selected"';
				echo ">$v</option>\n";
		  	}
	
//complete tag
			echo '</select>';
			
			//add error if one exists
			if(array_key_exists($name, $errors))
				{
					echo '<br /><span class="error">'. $errors[$name] . '</span>';
				}
				
							//added in chapter 11 admin page 344
			}elseif ($type ==='textarea')
				{
					if(array_key_exists($name, $errors))
					 echo ' <span class="error">' . $errors[$name]. '</span><br />';
					echo '<textarea name="' . $name .'" id="'.$name.'" rows="5" cols="75" ';
				 
					
					if(array_key_exists($name, $errors))
						{
							echo 'class="error">';
						}else{
							echo '>';
						}

					if($value) echo $value;
					echo '</textarea>';
			//end added cp 11 page 344
			
		}//end of primary IF-ELSE
	}//End of the create_form_input() function