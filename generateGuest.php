<?php
session_start();
require 'db.php';
require 'mailConfigure.php';

if(isset($_POST['firstname'])){

    
//Get data
$firstname=mysqli_real_escape_string($db_conn,$_POST['firstname']);
$lastname=mysqli_real_escape_string($db_conn,$_POST['lastname']);
$email=mysqli_real_escape_string($db_conn,$_POST['email']);
$accesscode=mysqli_real_escape_string($db_conn,$_POST['accesscode']);
$ac_hash=password_hash($accesscode,PASSWORD_DEFAULT);
$description=mysqli_real_escape_string($db_conn,$_POST['description']);
$uuid = abs( crc32( uniqid() ) );
$sessionCreator = $_SESSION['username'];
$time = $_SERVER['REQUEST_TIME'];

if(empty($firstname) || empty($lastname) || empty($accesscode)){
    end();
}
else{
    
    //Insert into database
    mysqli_query($db_conn,"INSERT INTO guest (uuid, accessCode,first_name,last_name,sessionCreator,email, time, description) VALUES('$uuid','$ac_hash','$firstname','$lastname','$sessionCreator','$email', $time, '$description')");
    
    
    //Insert log
    date_default_timezone_set("Asia/Kuala_Lumpur");
    $date = date("d-m-Y")." ". date("H:i:s"). " UTC +8";
    
    mysqli_query($db_conn, "INSERT INTO log (`date`, `action`, `description`) 
                            VALUES 
                            ('$date','Guest Session','A session has been created by $sessionCreator for $firstname $lastname')");
    
    try {
    
    //Send mail to receipent
    $mail->addAddress($email);             
    $mail->Subject = 'File Session';
    $mail->Body    = 'Hi, '.$firstname.' '.$lastname.'<br>
                      '.$sessionCreator.' has created a session for you to login to our system.<br><br>
                      Universally Unique Identification(UUID): '.$uuid.'<br>
                      Access Code aka. Password: '.$accesscode.'<br>
                      Description: '.$description.'<br><br>
                      <strong>Terms & Condition applied</strong><br>
                      A link is attached below.<br><br>
                      This is an automated generated email, please do not reply<br>
                      Regards,<br>
                      <strong>THE FILE SOLUTION</strong>';
        
    $mail->send();
 
    } catch (Exception $e) {
        //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        echo 'Email could not be sent. ';
    }
    
    
    //Return
    $data = array($uuid,$accesscode);
    echo json_encode($data);
}
    
}