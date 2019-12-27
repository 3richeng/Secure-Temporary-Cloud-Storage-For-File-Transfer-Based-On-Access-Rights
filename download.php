<?php

    $path = $_GET['path'];
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($path).'"');
    header('Content-Length: ' . filesize($path));
    readfile($path);
    unlink($path);