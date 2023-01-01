<?php

// Declare ini File
$ini = parse_ini_file('sql/config.ini');

// Create ini Array
$db_info = array(
    "db_host"    => $ini['db_host'],
    "db_user"    => $ini['db_user'],
    "db_pass"    => $ini['db_pass'],
    "db_name"    => $ini['db_name'],
    "db_host_ip" => $ini['db_host_ip']
);

// Declare ini Variables
$host       = $db_info['db_host'];
$username   = $db_info['db_user'];
$password   = $db_info['db_pass'];
$database   = $db_info['db_name'];
$hostIP     = $db_info['db_host_ip'];

//Setup Connection
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die('Unable to Connect to the Database!' . mysqli_connect_error());
}
