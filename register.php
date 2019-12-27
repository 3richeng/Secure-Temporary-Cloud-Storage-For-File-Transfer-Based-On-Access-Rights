<?php

    require 'db.php';
    session_start();

    if(isset($_POST['username']))
    {
        //Escape all $_POST variables to protect against SQL injections
        $username=mysqli_real_escape_string($db_conn,$_POST['username']);
        $email=mysqli_real_escape_string($db_conn,$_POST['email']);
        $firstname=mysqli_real_escape_string($db_conn,$_POST['firstname']);
        $lastname=mysqli_real_escape_string($db_conn,$_POST['lastname']);
        $password=mysqli_real_escape_string($db_conn,$_POST['password']); //Use Blowfish Algorithm to store password
        

        //Error handle
        
        //Check for empty field    
        if(empty($username) || empty($email) || empty($firstname) || empty($lastname) || empty($password))
        {
             echo '1'; 
        }
        else
        {
            //Check for invalid characters for first and last name
            if(!preg_match("/^[a-zA-Z]*$/",$firstname) || !preg_match("/^[a-zA-Z]*$/",$lastname))
            {
                    
                echo '2'; 
            }
            else
            {
                //Check for valid email
                if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    echo '3';
                }
                else
                {
                    //Check for existing user and email
                    $sql = "SELECT * FROM accounts WHERE username='$username' OR email='$email'";
                    $result = mysqli_query($db_conn, $sql);
                    $check = mysqli_num_rows($result);
                        
                    if($check > 0)
                    {
                           
                        echo '4';
                    }
                    else
                    {
                        //Check for strong password
                        if(strlen($password) < 10 || !preg_match("#[0-9]+#", $password) || !preg_match("#[A-Z]+#", $password) || !preg_match("#[a-z]+#", $password) || !preg_match("#[\W]+#", $password) )
                        {            
                            echo '5';
                                
                        }
                        else
                        {
                        
                            $password= password_hash($_POST['password'],PASSWORD_DEFAULT); //HASH PASSWORD
                            
                            //Insert data into database
                            $firstname = ucfirst($firstname); $lastname = ucfirst($lastname); $username = strtolower($username); $email = strtolower($email);
                                
                            mysqli_query($db_conn,
                                        "INSERT INTO accounts (`username`, `first_name`, `last_name`, `email`, `password`, `status`, `rank`) 
                                        VALUES 
                                        ('$username', '$firstname', '$lastname', '$email', '$password', 'Disable', 'User')");
                                
                            date_default_timezone_set("Asia/Kuala_Lumpur");
                            $date = date("d-m-Y")." ". date("H:i:s"). " UTC +8";
                                
                            mysqli_query($db_conn,
                                        "INSERT INTO log (`date`, `action`, `description`) 
                                        VALUES 
                                        ('$date','User Registration','$username has created an inactive account')");
                            
                            
                            
                             echo '6';   
                        }
                    }           
                }
            }
            
        }
    }


?>

