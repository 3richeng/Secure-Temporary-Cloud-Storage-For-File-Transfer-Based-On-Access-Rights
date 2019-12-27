<?php

define('FILE_ENCRYPTION_BLOCKS', 10000);

function encryptFile($source, $key, $dest)
{
    $key = hex2bin(hash('sha256', $key));
    
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));

    $error = false;
    if ($fpOut = fopen($dest, 'w')) //open file with write
    { 
        // Put the initialzation vector to the beginning of the file
        fwrite($fpOut, $iv); //write IV destination file 
        if ($fpIn = fopen($source, 'rb')) // open source file 
        {
            while (!feof($fpIn)) //if not end of file
            {
                $plaintext = fread($fpIn, 16 * FILE_ENCRYPTION_BLOCKS); //get string of code from source file
                $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv); //encrypt
                // Use the first 16 bytes of the ciphertext as the next initialization vector
                $iv = substr($ciphertext, 0, 16);
                fwrite($fpOut, $ciphertext); //write to destination file
            }
            fclose($fpIn);
        } 
        else 
        {
            $error = true;
        }
        fclose($fpOut);
    } 
    else 
    {
        $error = true;
    }

    return $error ? false : $dest;
}

/*Notes

fopen(destination, write only)

fwrite(fopen, string of text)

fread() 

*/