<?php
$servername = "localhost";
$username = "Alex";
$password = "25386";
$dbname = "album";
// Create connection
$bd = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($bd->connect_error) {
    die("Connection failed: " . $bd->connect_error);
}

?>