<?php

// Create a Random String (Not Used)
// Parameters:
// length = Static length used for the length of the string, adjust as needed
function str_rand(int $length = 64)
{ // 64 = 32
    $length = ($length < 4) ? 4 : $length;
    return bin2hex(random_bytes(($length - ($length % 2)) / 2));
}

// Ecrypt or Decrypt a String
// Parameters:
// action = ('encrypt' = encrypt password, 'decrypt' = decrypt password)
// string = The string used for the encrpytion or decryption
function encrypt_decrypt($action, $string)
{
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'xxxxxxxxxxxxxxxxxxxxxxxx';
    $secret_iv = 'xxxxxxxxxxxxxxxxxxxxxxxxx';
    // Hash - SHA256 method
    $key = hash('sha256', $secret_key);
    // Vector (IV) - Encrypt method AES-256-CBC expects 16 bytes 
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output ?? '');
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string ?? '') ?? '', $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
