<?php
require 'db.php';
//Include database connection

if(isset($_POST['rowid']))
{
    $id = $_POST['rowid']; 
    $files = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT * FROM files WHERE id=$id"));
    $accesscontrol = mysqli_query($db_conn,"SELECT username, status FROM accesscontrol WHERE fileId=$id");
    
    $attr = array(
        $files['id'],               //0
        $files['name'],             //1
        $files['description'],      //2
        $files['type'],             //3
        $files['size'],             //4
        $files['date'],             //5
        $files['level'],            //6
        $files['status'],           //7
        $files['publicity'],        //8
        $files['path']);            //9
    
    $access = array();
    while($row = mysqli_fetch_assoc($accesscontrol)){
        $access[] = $row;
    }
    //print_r(array($attr,$access));
    echo json_encode(array($attr,$access));
        
}