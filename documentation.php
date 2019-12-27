<?php

if (isset($_GET['download']))
{
    $username = $_SESSION['username'];
    $path = $_GET['download'];
    $fileName = basename($path);    
    $fileTmpLocation = 'Temporary/'.$fileName;
    $fileToCloud = 'ToCloud/'.$fileName;
    
    $filesQuery = mysqli_query($db_conn, "SELECT * FROM files WHERE path='$path'") or die("Error: No path found");
    $files = mysqli_fetch_assoc($filesQuery);
    
    if (mysqli_num_rows($filesQuery) != 1) { die("Error: No results found");}
    
    $fileId = $files['id'];
    $keyPath = $files['path'];
    
    //Connect to AWS
    $s3 = S3Client::factory(
    array(
        'credentials' => array(
        'key' => $IAM_KEY,
        'secret' => $IAM_SECRET
        ),
    'version' => 'latest',
    'region'  => 'ap-southeast-1'
        )
    );
    
    $result = $s3->getObject(array(
        'Bucket' => $BUCKET_NAME,
        'Key'    => $keyPath
    ));
    
    $keyQuery = mysqli_query($db_conn,"SELECT * FROM keymanagement WHERE fileId='$fileId'");
    $key = mysqli_fetch_assoc($keyQuery);
    $decryptionKey = $key['cryptoKey'];
    
    //Get object and decrypt to web server
    file_put_contents($fileToCloud,$result['Body']); 
    decryptFile($fileToCloud,$decryptionKey,$fileTmpLocation);
    unset($decryptionKey);
        
    //Update database
    mysqli_query($db_conn,"UPDATE accesscontrol SET status = 1 WHERE fileId ='$fileId' AND username = '$username'");
    
    unlink('ToCloud/'.$fileName);
    
    //If all downloaded, update database
    $accessQuery = mysqli_query($db_conn,"SELECT * FROM accesscontrol WHERE fileId ='$fileId' AND status = 0 ");
    if(mysqli_num_rows($accessQuery) == 0)
    {
        mysqli_query($db_conn,"UPDATE files SET status = 'Completed' WHERE id ='$fileId'");
        
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $date = date("d-m-Y")." ". date("H:i:s"). " UTC +8";
        
        mysqli_query($db_conn, "INSERT INTO log (`date`, `action`, `description`) 
                                VALUES 
                                ('$date','File Completion','$fileName by $username is completed')");
        
        //Delete from cloud
        $result = $s3->deleteObject(array(
        'Bucket' => $BUCKET_NAME,
        'Key'    => $keyPath,
    ));
        //Notify sender the file is completed
        try {
            
            echo $accounts['email'];
            $owner = $files['owner'];
            $accounts = mysqli_fetch_assoc(mysqli_query($db_conn, "SELECT * FROM accounts WHERE username='$owner'"));
            
            $mail->addAddress($accounts['email']);   
            $mail->Subject = 'File Completed';
            $mail->Body    =    'Hi '.$username.',<br> 
                                The file you have uploaded to the cloud is completed.<br><br>
                                <strong>Identification  : </strong>'.$files['id'].'<br>
                                <strong>File Name       : </strong>'.$files['name'].'<br>
                                <strong>Description     : </strong>'.$files['description'].'<br>
                                <strong>Format          : </strong>'.$files['type'].'<br>
                                <strong>Size            : </strong>'.$files['size'].'<br>
                                <strong>Date            : </strong>'.$files['date'].'<br>
                                <strong>Publicity       : </strong>'.$files['publicity'].'<br>
                                <strong>Sensitivity     : </strong>'.$files['level'].'<br><br>
                                This is an automated generated email, please do not reply<br>
                                Regards,<br>
                                <strong>THE FILE SOLUTION</strong>';
            $mail->send();
            }catch (Exception $e) {
                echo 'Email could not be sent. ';
                //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            }
        
    }
    
           
    //Head to download
    header("location: download.php?path=$fileTmpLocation");

}
