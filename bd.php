<?php

$servername = "localhost";
$username = "User";
$password = "123456789";
$dbname = "album";
// Create connection
$bd = new mysqli($servername, $username, $password,$dbname);


if ($bd->connect_error) {
    die("Connection failed: " . $bd->connect_error);
}
?>