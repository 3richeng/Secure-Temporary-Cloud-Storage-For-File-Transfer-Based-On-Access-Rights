<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'phpMailer/vendor/autoload.php';

$mail = new PHPMailer(true);                                    // Passing `true` enables exceptions

    //Server settings
    //$mail->SMTPDebug = 1;                                     // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                             // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                                     // Enable SMTP authentication
    $mail->Username = 'thefilesolution@gmail.com';              // SMTP username
    $mail->Password = '5bab2be4d668b3441d3133216ca779be';       // SMTP password
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption
    $mail->Port = 587;                                          // TCP port to connect to
    $mail->setFrom('thefilesolution@gmail.com', 'The File Solution');
    $mail->isHTML(true);

