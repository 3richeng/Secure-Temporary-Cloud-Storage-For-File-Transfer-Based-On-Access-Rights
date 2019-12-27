<?php

require 'db.php';
require 'aws.php';
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

session_start();



if (isset($_POST['path']))
{

    $keyName = $_POST['path'];
    $id = $_POST['id'];

    // Connect to AWS
    $client = new Aws\S3\S3Client([
        'version' => 'latest',
        'region'  => 'ap-southeast-1',
            'credentials' => [
            'key'      => $IAM_KEY,
            'secret'   => $IAM_SECRET,
        ],
    ]);

    //Delete file/object
    $result = $client->deleteObject(array(
        'Bucket' => $BUCKET_NAME,
        'Key'    => $keyName,
    ));
    
    
    //Update database
    $sql = "UPDATE files SET status = 'Deleted' WHERE id=$id"; 
    mysqli_query($db_conn,$sql);
    
}
  



?>