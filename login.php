<?php

    require 'db.php';
    session_start();

    if(isset($_POST['username']) && isset($_POST['password']))
    {
        
        //Escape all $_POST variables to protect against SQL injections
        $username=mysqli_real_escape_string($db_conn,$_POST['username']);
        $password=mysqli_real_escape_string($db_conn,$_POST['password']);
                
    //Error handlers
    //Check for empty fields
        if(empty($username) || empty($password))
        {
            //echo "Missing field detected\n";
            echo "error_1";
        }
        else
        {
            $sql = "SELECT * FROM accounts WHERE username ='$username'";
            $result = mysqli_query($db_conn,$sql);
            $check = mysqli_num_rows($result);
            
            
            if($check < 1)
            {
                //echo "The username is not associated with any account.<br>Please try again";
                echo "error_2";
            }
            else
            {
                 
                if($row = mysqli_fetch_assoc($result))
                {
                    $hashpassword = password_verify($password,$row['password']);
                    
                    if($hashpassword == false)
                    {
                        //echo "The password that you've entered is incorrect.<br>Please try again.";
                        echo "error_3";
                    }
                    else if ($hashpassword == true)
                    {
                        
                        if($row['status'] == "Active")
                        {
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['firstname'] = $row['first_name'];
                        $_SESSION['lastname'] = $row['last_name'];
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['rank'] = $row['rank'];
                        echo "proceed";
                        }
                        
                        if($row['status'] == "Disable")
                        {
                            echo "error_4";
                        }
                    }
                }
                else
                {
                    //echo "An Error has occur.<br>";
                    echo "error_5";
                }

            }
        }

    }

    
    
?>