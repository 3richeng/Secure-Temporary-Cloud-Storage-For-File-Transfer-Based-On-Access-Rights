<?php
require 'db.php';

if(isset($_POST['status'])){
    
$status = $_POST['status'];
$id = $_POST['id'];
$rank = $_POST['rank'];

//Disable account
if ($status == 'disable')
{
    if($rank == 'Admin')
    {
        echo "admin";
    }
    else
    {
        mysqli_query($db_conn,"UPDATE accounts SET status='Disable' WHERE id=$id");   
        echo "disabled";
    }
    
}
//Activate account
else if ($status == 'activate')
{
    $result = mysqli_query($db_conn,"UPDATE accounts SET status='Active' WHERE id=$id");
    echo "activated";
}
else
{
    echo "false";
}

}