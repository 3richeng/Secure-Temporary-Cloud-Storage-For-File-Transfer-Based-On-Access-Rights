<?php

require 'db.php';
require 'aws.php';
require 'encryption.php';
require 'mailConfigure.php';
require 'fileSize.php';
require 'vendor/autoload.php';

	
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

session_start();


if(isset($_SESSION['username']))
{
// Connect to AWS
try 
{

	$s3 = S3Client::factory
    (
	   array
        (
            'credentials' => array
            (
                'key' =>  $IAM_KEY,
                'secret' => $IAM_SECRET
            ),
            
            'version' => 'latest',
            'region'  => 'ap-southeast-1'
        )
    );
} 
catch (Exception $e) {die("Error: " . $e->getMessage());}

    //Get data
    $owner = $_SESSION['username'];
    $password=mysqli_real_escape_string($db_conn,$_POST['filePassword']);
    $hashPassword = password_hash($password,PASSWORD_DEFAULT);
    $description = $_POST['description'];
    $level = $_POST['level'];
    $publicity = $_POST['publicity'];
    $guest = $_POST['guest'];
    $fileCount = count($_FILES['file']['tmp_name']);
    
     //echo '<pre>', print_r ($_FILES), '</pre>'; //developer usage only

    $allowedType = 'true';
    $fileError = 'false';

    //Allowed file type
    $allowed = array('txt','jpg','jpeg','png','pdf','mp3','aif','pkg','rar','zip','log'.'xml','sql','db','jar','png','js','java','doc','docx','mp4','mp3','php','xls','pptx','html','css');


    for($i=0; $i < $fileCount; $i++)
    {
        $fileExt = explode('.', $_FILES['file']['name'][$i]);
        $fileActualExt = strtolower(end($fileExt));
        
        //Check every file type
        if(!in_array($fileActualExt, $allowed)){ $allowedType = 'false'; }
        if($_FILES['file']['error'][$i] != 0)  { $fileError   = 'true'; }
    }


    if($allowedType == 'true')
    {
        
        if($fileError == 'false')
        {
            

                
                if($fileCount == 1) //Single File Upload
                { 
                    
                    
                    $fileName = $_FILES['file']['name'][0];
                    $fileNameNew = uniqid().".".$_FILES['file']['name'][0];
                    $fileTmpLocation = 'Temporary/'.$fileNameNew;
                    
                    $fileType = $_FILES['file']['type'][0];
                    
                    $size = $_FILES['file']['size'][0];    
                    $fileSize = sizeOfFile($size);
                    
                    move_uploaded_file($_FILES['file']['tmp_name'][0], $fileTmpLocation);
                    
                    
                
                }
                else //Multiple File Upload
                {
                    $filesToZip = array();
                    
                    foreach($_FILES['file']['tmp_name'] as $key => $tmp_name)
                    {
                        $fileName=$_FILES['file']['name'][$key];
                        $fileTmpLocation = 'Temporary/'.$fileName;
                        array_push($filesToZip,$fileName);
                        move_uploaded_file($_FILES['file']['tmp_name'][$key], "$fileTmpLocation");
                    }
                    
                    $fileName = uniqid().'.zip';
                    $fileNameNew = $fileName;
                    $fileType = 'application/zip';
                    
                    $zip = new ZipArchive;
                    $zip_name = 'Temporary/'.$fileNameNew;
                    
                    if($zip->open($zip_name,ZipArchive::CREATE) === TRUE)
                    {
                        foreach($filesToZip as $file)
                        { $zip->addFile('Temporary/'.$file, $file); }
                        
                        $zip->close();
                        
                    }
                        $size = filesize($zip_name);
                        $fileSize = sizeOfFile($size);
                    
                    
                        foreach($filesToZip as $file)
                        { unlink('Temporary/'.$file); }
                    
                    
                }

                $fileTmpLocation = 'Temporary/'.$fileNameNew;
                $fileToCloud = 'ToCloud/'.$fileNameNew;
                $keyName = 'encryptedFiles/' . $fileNameNew;
                $pathInS3 = 'https://s3-ap-southeast-1.amazonaws.com/mmu-fyp-bucket/' . $BUCKET_NAME . '/' . $keyName;
                    
                //Validition
                $md5 = md5_file($fileTmpLocation);
                $sha1 = sha1_file($fileTmpLocation);
               
                  
                //Generate Encryption Key
                $key = bin2hex(random_bytes(32));
                encryptFile("$fileTmpLocation", "$key" , "$fileToCloud");
                
                
                //Post file to AWS Cloud
                try 
                {
                    $s3->putObject
                    (
                        array
                        (
				            'Bucket'=>$BUCKET_NAME,
				            'Key' =>  $keyName,
				            'SourceFile' => $fileToCloud,
				            'StorageClass' => 'REDUCED_REDUNDANCY',
                            //'ACL' => 'public-read'
			             )
                    );
                    
                echo '<div class="alert alert-success"><strong>SUCCESS: </strong>User request has been submitted</div>';   
            
                }
                
                catch (S3Exception $e) {die('<div class="alert alert-danger"><strong>ERROR: </strong> Failed to connect to the cloud server');} 
                catch (Exception   $e) {die('Error:' . $e->getMessage());}
            
                unlink('Temporary/'.$fileNameNew);
            
                //Get time
                date_default_timezone_set("Asia/Kuala_Lumpur");
                $date = date("d-m-Y")." ". date("H:i:s"). " UTC +8";
                
                //Insert file attributes to database
                mysqli_query($db_conn,
                             "INSERT INTO files(name, type, size, path, owner, password, date, description, level, status, publicity, md5, sha1) 
                             VALUES 
                             ('$fileName','$fileType','$fileSize','$keyName','$owner','$hashPassword','$date', '$description' ,'$level','Pending','$publicity','$md5','$sha1')"); 
                   
                //$query = "SELECT * FROM files WHERE fileName='$fileName'"; 
                //$fileId = mysqli_query($db_conn,"SELECT * FROM files WHERE fileName='$fileName'");
                $files = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT * FROM files WHERE path='$keyName'"));
                $id = $files['id'];
                     
                //Insert key to database
                $query = "INSERT INTO keymanagement(fileId,cryptoKey) VALUES ($id,'$key')";
                mysqli_query($db_conn,$query);  
            
           
                //Insert access data to database
                if($guest == "true")
                {
                    $user = $_SESSION['sessionCreator'];
                    
                    mysqli_query($db_conn,"INSERT INTO accesscontrol (fileId, username, status) VALUES ('$id','$user','0')");
                    
                    $accounts = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT * FROM accounts WHERE username='$user'"));
                    
                    $email = $accounts['email'];
                    $mail->addAddress($email);  
                }
                else
                {
                    $accountId = $_POST['name'];
                    $email = array();
                    $i=0;
                    if($accountId)
                    {
                        foreach($accountId as $user)
                        {   
                            mysqli_query($db_conn,"INSERT INTO accesscontrol (fileId, username, status) VALUES ('$id','$user','0')");
                            
                            //Get user email
                            $accounts = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT * FROM accounts WHERE username='$user'"));
                            $email[$user] = $accounts['email'];
                            $mail->addAddress($email[$user]);    
                        }   
                    }
                    
                    
                }
            
                //Insert log
                mysqli_query($db_conn, "INSERT INTO log (`date`, `action`, `description`) 
                                        VALUES 
                                        ('$date','File Upload','$owner uploaded a file: $fileName')");
            
            
            
            
            
                unset($key);//destroy key
                
            
            //Send email
            try {
            $mail->Subject = 'File Download';
            $mail->Body    =    'Hi,<br> 
                                You have been selected to download this <strong> '.$level.'</strong> and <strong>'.$publicity.'</strong> file (Name:'.$fileName.' | Identification: '.$id.') uploaded by '.$owner.'<br> 
                                To download, please login to the The File Solution!<br><br>
                                The password for the file <strong style="color:red">(Please do not share the password)</strong>: '.$password.'<br>
                                Description: '.$description.'<br><br>
                                A link is attached below.<br><br>
                                This is an automated generated email, please do not reply<br>
                                Regards,<br>
                                <strong>THE FILE SOLUTION</strong>';
            $mail->send();
            }catch (Exception $e) {
                echo 'Email could not be sent. ';
                //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            }
                            
           
                
               
                    
        }
        else
        {
            echo '<div class="alert alert-danger"><strong>ERROR: </strong>An Error Has Occur.</div>'; 
        }

    }
    else
    {
        echo '<div class="alert alert-danger"><strong>ERROR: </strong>Invalid File Detected!</div>';    
    }

    
}
  



?>