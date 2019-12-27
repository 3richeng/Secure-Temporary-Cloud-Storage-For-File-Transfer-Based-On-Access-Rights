<?php

require 'db.php';

//get files database
$files = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as files FROM files"));
$files = $files['files'];

$completed = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as completed FROM files WHERE status='Completed'"));
$completed = $completed['completed'];

$pending = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as pending FROM files WHERE status='Pending'"));
$pending = $pending['pending'];

$deleted = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as deleted FROM files WHERE status='Deleted'"));
$deleted = $deleted['deleted'];

$public = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as public FROM files WHERE publicity='Public'"));
$public = $public['public'];

$private = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as private FROM files WHERE publicity='Private'"));
$private = $private['private'];

//get user database
$users = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as users FROM accounts"));
$users = $users['users'];

$active = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as active FROM accounts WHERE status='Active'"));
$active = $active['active'];

$disable = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT COUNT(*) as disable FROM accounts WHERE status='Disable'"));
$disable = $disable['disable'];

$fileData = array($files, $completed, $pending, $deleted, $public, $private);
$accounts = array($users, $active, $disable) ;

echo json_encode(array($fileData,$accounts));

?>