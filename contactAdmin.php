<?php

require 'mailConfigure.php';

if(isset($_POST['subject'])){
    
$subject = $_POST['subject'];
$email = $_POST['email'];
$description = $_POST['description'];
$admin = 'thefilesolution@gmail.com';
    
    try 
    {
        $mail->addAddress($admin);
        $mail->Subject = $subject;
        $mail->Body    = '<strong>FROM:</strong> '.$email.'<br><br>
                         <strong>DESCRIPTION: </strong>'.$description.'';
            
        $mail->send();
        echo "true";
    }
    catch (Exception $e) 
    {
        echo "false";
        //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}


