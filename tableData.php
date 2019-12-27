<?php

session_start();
require 'db.php';

if(isset($_SESSION['username']))
{

$view = intval($_GET['view']);
$username = $_SESSION['username'];

//Different data view
if($view == 1)
{$sql="SELECT * FROM files WHERE publicity='Public' AND status='Pending'";}
if($view == 2)
{$sql = "SELECT * from files INNER JOIN accesscontrol ON files.id = accesscontrol.fileId 
        WHERE accesscontrol.username='$username' AND accesscontrol.status='0' AND files.status='Pending';";}
if($view == 3)
{$sql = "( SELECT * FROM files WHERE owner='$username' AND status ='Pending' ORDER BY id DESC LIMIT 100 ) ORDER BY id ASC;";}
if($view == 4)
{$sql = "( SELECT * FROM files WHERE owner='$username' AND status ='Completed' ORDER BY id DESC LIMIT 100 ) ORDER BY id ASC;";}  
    
$result = mysqli_query($db_conn,$sql); 

//store data in array
$data = array();
while($files=mysqli_fetch_assoc($result))
{
    $data[] = $files;
}

echo json_encode($data);
    
}