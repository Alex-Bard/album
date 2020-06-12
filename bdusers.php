<?php

$servername = "localhost";
$username = "User";
$password = "123456789";
$dbname = "album";

$bd = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($bd->connect_error) {
    die("Connection failed: " . $bd->connect_error);
}

?>