<?php

//AWS RDS Database connection
$host='mmu-fyp-rds.cfedl310pauh.ap-southeast-1.rds.amazonaws.com';
$user='mmufyprds';
$pass='66781841E29EE5B8C8B91662DEE7E79FD987C6B7';
$db_name='filesystem';

//$db_conn = new mysqli($host,$user,$pass,$db_name) or die($mysqli->error);
$db_conn = new mysqli($host,$user,$pass,$db_name) or die("Failed to connect to database");


