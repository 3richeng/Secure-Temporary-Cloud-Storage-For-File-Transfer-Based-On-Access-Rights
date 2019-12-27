<?php

require 'db.php';

if(isset($_POST['id']))
{
    
    $fileName = $_FILES['file']['name'];
    $fileTmp = $_FILES['file']['tmp_name'];
    $fileTmpLocation = 'Hash/'.$fileName;
    $path = 'encryptedFiles/'.$fileName;
    $id = $_POST['id'];
    $result = "";
   
    move_uploaded_file($_FILES['file']['tmp_name'], $fileTmpLocation);

    $upload_md5 = md5_file($fileTmpLocation);
    $upload_sha1 = sha1_file($fileTmpLocation);
    unlink($fileTmpLocation);

    //Fetch data from database
    if(empty($id)) //Used file to verify
    {
    
        //Verify if no input
        $sql = mysqli_query($db_conn,"SELECT * FROM files WHERE path='$path'");
        $files = mysqli_fetch_assoc($sql);

    }
    else    //Used ID to veify
    {
        $sql = mysqli_query($db_conn,"SELECT * FROM files WHERE id='$id'");
        $files = mysqli_fetch_assoc($sql);
    }

    //Compare fetched data
    if(mysqli_num_rows($sql)==0)
    { 
        $result = "error";
    
    }
    else
    {

        $db_md5 = $files['md5'];
        $db_sha1 = $files['sha1'];

        if($upload_md5 == $db_md5 && $upload_sha1 == $db_sha1){
            $result = "true";
        }
        else{
            $result = "false";
        }
    }
    
    //Insert data to array
    $data = array($upload_sha1,$upload_md5,$result);

    //return array
    echo json_encode($data);

}



