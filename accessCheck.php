<?php

require 'db.php';
session_start();

if(isset($_POST['id'])){
    
$id = $_POST['id'];
$path = $_POST['path'];
$username = $_SESSION['username'];
$password=mysqli_real_escape_string($db_conn,$_POST['password']);


$accessQuery = mysqli_query($db_conn,"SELECT * FROM accesscontrol WHERE fileId ='$id' AND username = '$username'");
$user = mysqli_num_rows($accessQuery);
$access = mysqli_fetch_assoc($accessQuery);
$status = $access['status'];

//Given access
if($user > 0)
{
    //Haven't download
    if($status == 0)
    {
        //Verify password
        $result = mysqli_query($db_conn,"SELECT * FROM files WHERE path ='$path'");
    
        if($files = mysqli_fetch_assoc($result))
        {
        
            $hashpassword = password_verify($password,$files['password']);
         
            if($hashpassword == "true")
            { 
                echo "true"; 
            }
            else 
            { 
                echo "error_1"; 
            }
        }
        else
        {
            echo "error_4";
        }
    }
    //Downloaded
    else if($status == 1)
    {
        echo "error_2";
    }
    else
    {
        echo "error_4";
    }
    
}

//No access
else
{
    echo "error_3";
}

}







?>