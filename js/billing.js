//used for stripe
//billing.js
$(function(){


	$('#billing_form').submit(function(){
		//create flag variable
		var error = false;
		//disable submit button
		$('input[type=submit]' , this).attr('disabled', 'disabled');

		//retrieve form values
			
			
	       var cc_number = $('#cc_number').val(),
	       cc_cvv = $('#cc_cvv').val(), 
	       cc_exp_month = $('#cc_exp_month').val(), 
	       cc_exp_year = $('#cc_exp_year').val();
				
		
			//validate credit card number
			if(!Stripe.validateCardNumber(cc_number))
				{
					error = true;
					reportError('The credit card number appears to be invalid.');
				}
			if(!Stripe.validateCVC(cc_cvv))
				{
					error = true;
					reportError('The CVV number appears to be invalid.');
				}
			if(!Stripe.validateExpiry(cc_exp_month, cc_exp_year))
		
				{
					error = true;
					reportError('The expiration date appears to be invalid');
				}
		
		  //if no errors request the token from stripe
		 if(!error)
		  	{
		  		Stripe.createToken({
		  			number: cc_number,
		  			cvc: cc_cvv,
		  			exp_month: cc_exp_month,
		  			exp_year: cc_exp_year
		  		}, stripeResponseHandler);
		  	}
		 return false; 	
		  	
	}); //form submission
}); //document ready


//Function handle Stripe response
//function stripeResponseHandler(status, response) {
function stripeResponseHandler(status, response){
	// Check for an error:
	//if (response.error) {
	//check for errors
	if(response.error){
		//reportError(response.error.message);
		reportError(response.error.message);
	//} else { // No errors, submit the form.
	}else{ //no errors, submit form
	  //var billing_form = $('#billing_form');
	  var billing_form =$('#billing_form');
	  // token contains id, last4, and card type
	  //token contains is, last 4 and card type
	 // var token = response.id;
	  var token = response.id;
	  // insert the token into the form so it gets submitted to the server
	 // billing_form.append("<input type='hidden' name='token' value='" + token + "' />");
	  //insert the token into the form so it gets submitted to the server
	  billing_form.append("<input type='hidden' name='token' value='"+token+"' />");
	  // and submit
	  //billing_form.get(0).submit();
	  billing_form.get(0).submit();
	 
	}
	
//} // End of stripeResponseHandler() function.
} // End of stripeResponseHandler() function
//define reportError() function

function reportError(msg)
	{
		//show error in form
		$('#error_span').text(msg);
		$('input[type=submit]', this).attr('disabled', false);
		return false;
	}
 


