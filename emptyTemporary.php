<?php

    $files = glob('ToCloud/*'); // get all file names
    foreach($files as $file)
    { // iterate files
        if(is_file($file))
        unlink($file); // delete file
    }

    $files = glob('Temporary/*'); // get all file names
    foreach($files as $file)
    { // iterate files
        if(is_file($file))
        unlink($file); // delete file
    }