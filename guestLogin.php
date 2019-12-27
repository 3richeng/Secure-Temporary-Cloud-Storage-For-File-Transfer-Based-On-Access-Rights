<?php

require 'db.php';
session_start();
define('TIMEOUT_DURATION', 3600);

if(isset($_POST['uuid']) && isset($_POST['accesscode']))
{
//Get data
$uuid=mysqli_real_escape_string($db_conn,$_POST['uuid']);
$accesscode=mysqli_real_escape_string($db_conn,$_POST['accesscode']);
    
    //Data validation
    if(empty($uuid) || empty($accesscode))
    {
        
        echo "error_1";
    }
    else
    {
        $query = mysqli_query($db_conn,"SELECT * FROM guest WHERE uuid =$uuid");
        $check = mysqli_num_rows($query);
        
        if($check < 1)
        {
            echo "error_2";
        }
        else
        {
            if($guest = mysqli_fetch_assoc($query))
            {
                $ac_verify = password_verify($accesscode,$guest['accessCode']);
                
                if($ac_verify == false)
                {
                    echo "error_3";
                }
                if($ac_verify == true)
                {

                        if($_SERVER['REQUEST_TIME'] - $guest['time'] > TIMEOUT_DURATION) //Timeout out after 1 hour
                        {
                            echo "error_4";
                        }
                        else
                        {
                            //Access approved
                            $_SESSION['username'] = $guest['uuid'];
                            $_SESSION['firstname'] = $guest['first_name']; $_SESSION['lastname'] = $guest['last_name'];
                            $_SESSION['time'] = $guest['time'];
                            $_SESSION['sessionCreator'] = $guest['sessionCreator'];
                            echo "true";
                        }
                }
                
                
                
                
                
            }
            else
            {
                echo "error_5";
            }
            
        }
    }
   
    
}