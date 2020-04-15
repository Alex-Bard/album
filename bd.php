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
function addFileToBd($name,$orig,$miniature,$date,$bd,$model,$lat,$long){
    $sql = "INSERT INTO files (nameFile, orig, miniature, dateFile,model,lat,Clong )
VALUES ('$name', '$orig', '$miniature','$date','$model','$lat','$long')";
    if ($bd->query($sql) === TRUE) {
        return true;
    } else {
        print_r ($bd->error);
    }
}

?>