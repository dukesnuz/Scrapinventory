<?php
/**************************************************************************************************
*****function to take arguments and then send an email using mailgun and phh mailer****************
***************************************************************************************************/
function sendMail($data){
  //print_r($data);
  require_once ('./vendor/autoload.php');
  $mail = new PHPMailer;
  $mail->isSMTP();
  $mail->Host = MAILGUN_HOST;
  $mail->SMTPAuth = true;
  $mail->Username = MAILGUN_USERNAME;
  $mail->Password = MAILGUN_PASSWORD;
  $mail->SMTPSecure = 'tls';
  $mail->From = $data['fromEmail'];
  $mail->FromName = $data['fromFirstName'];
  $mail->addAddress($data['toEmail'], $data['toFirstName']);
  $mail->isHTML($data['isHTML']);
  $mail->Subject = $data['subject'];
  $mail->Body    = $data['body'];
  $mail->AltBody = $data['altBody'];

  if(!$mail->send()) {
    echo "<p class ='error'>OOPPs System Error</p>";
    //echo 'Mail Error: ' . $mail->ErrorInfo . "n";
  } else {
    echo "<p class ='error'>Success</p>";
  }

} //end function
