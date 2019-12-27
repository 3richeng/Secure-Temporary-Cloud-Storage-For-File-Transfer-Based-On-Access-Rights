<?php

require 'db.php';
session_start();

if(isset($_POST['username']))
{
    
    $username = mysqli_real_escape_string($db_conn,$_POST['username']);
    $password = mysqli_real_escape_string($db_conn,$_POST['password']);
    
    
    $accounts = mysqli_fetch_assoc(
    mysqli_query($db_conn,"SELECT * FROM accounts WHERE username ='$username'"));
    
    $hashpassword = password_verify($password,$accounts['password']);
    
    if($hashpassword == true){
        if($accounts['rank'] == "Admin"){
            echo "true";
        }
        else{
            echo "false";
        }
    }
    if($hashpassword == false){
        echo "false";
    }
    
}

?>