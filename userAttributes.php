<?php

require 'db.php';

if(isset($_POST['username'])){

$username = $_POST['username']; 

//Get user information attributes
$accounts = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT * FROM accounts WHERE username='$username'"));
$id = $accounts['id'];
$username = $accounts['username'];
$firstname = $accounts['first_name'];
$lastname = $accounts['last_name'];
$email = $accounts['email'];
$status = $accounts['status'];
$rank = $accounts['rank'];

//Get the user usage on web
$files = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as files FROM files WHERE owner='$username'"));
$files = $files['files'];

$completed = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as completed FROM files WHERE status='Completed' AND owner='$username'"));
$completed = $completed['completed'];

$pending = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as pending FROM files WHERE status='Pending' AND owner='$username'"));
$pending = $pending['pending'];

$deleted = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as deleted FROM files WHERE status='Deleted' AND owner='$username'"));
$deleted = $deleted['deleted'];

$public = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as public FROM files WHERE publicity='Public' AND owner='$username'"));
$public = $public['public'];

$private = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as private FROM files WHERE publicity='Private' AND owner='$username'"));
$private= $private['private'];

$info = array($id,$username,$firstname,$lastname,$email,$status,$rank);
$usage = array($files, $completed, $pending, $deleted, $public, $private);

echo json_encode(array($info,$usage));
    
}