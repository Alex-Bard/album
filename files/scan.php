<?php
require 'C:\wamp64\www\course\bd.php';
require 'C:\wamp64\www\course\miniature.php';
$files = scandir("full");
$sql = "DELETE  FROM files";
if ($bd->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . $bd->error;
}
foreach ($files as &$file) {
    if(strlen($file)>5) {
        $Latitude =$Longitude = "NULL";
        $date = "";
        //resize("C:/wamp64/www/course1.1/files/full/$file", "C:/wamp64/www/course1.1/files/thumb/m_$file", '300', '', '');
        cropImage("C:/wamp64/www/course/files/full/$file", "C:/wamp64/www/course/files/thumb/m_$file", 200,200);
        $date = trim(file_info_date("C:/wamp64/www/course/files/full/$file"),":");
        $model = file_info_model("C:/wamp64/www/course/files/full/$file");
        $cords = get_coords("C:/wamp64/www/course/files/full/$file");
        if($cords!= NULL) {
            $Latitude = strval($cords['GPSLatitude']);
            $Longitude = strval($cords['GPSLongitude']);
         }

       if(! addFileToBd($file,"files/full/$file","files/thumb/m_$file",$date,$bd,$model,$Latitude,$Longitude)){echo"error"; }
    }
}
$bd-> close();