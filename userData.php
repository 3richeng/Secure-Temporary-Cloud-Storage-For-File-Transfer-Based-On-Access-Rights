<?php

session_start();
require 'db.php';

if(isset($_SESSION['username']))
{
    
    $view = intval($_GET['view']);
    
    //Print active accounts
    if($view == 1)
    {$sql="SELECT * FROM accounts WHERE status = 'Active'";}
    //Print disable accounts
    if($view == 2)
    {$sql="SELECT * FROM accounts WHERE status = 'Disable'";}
    //Get Log
    if($view == 3)
    {$sql="SELECT * FROM log LIMIT 50";}

    $result = mysqli_query($db_conn,$sql); 

    //Store data in array
    $data = array();
    while($files=mysqli_fetch_assoc($result))
    {
        $data[] = $files;
    }

    echo json_encode($data);
    
}