<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "album";
// Create connection
$bd = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($bd->connect_error) {
    die("Connection failed: " . $bd->connect_error);
}

?>