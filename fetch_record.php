<?php
require 'db.php';
//Include database connection
if($_POST['rowid']) 
{
    $id = $_POST['rowid']; 
    $file = mysqli_fetch_assoc(mysqli_query($db_conn,"SELECT * FROM files WHERE id=$id"));
    $path = $file['path'];
    
    $attributes = array
                    (
                    $file['id'],            //0
                    $file['name'],          //1
                    $file['description'],   //2
                    $file['type'],          //3
                    $file['size'],          //4
                    $file['owner'],         //5
                    $file['date'],          //6
                    $file['level'],         //7
                    $file['path'],          //8
                    $file['md5'],           //9
                    $file['sha1']           //10
                    );
    
    echo json_encode($attributes);
    
 }
?>