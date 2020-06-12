<?php
require 'bd.php';
require 'miniature.php';
ini_set ("memory_limit", "5000M" );
$files = scandir("files/forAdding");

foreach ($files as &$file) {
    print_r($file);
    $Latitude = $Longitude = "NULL";
    $date = "";
    $model = "";
    if(strlen($file)>5) {
        $tipeFile = mime_content_type("files/forAdding/$file");
        print_r($tipeFile);
        if (strripos($tipeFile, "image") !== false) {
            $date = trim(file_info_date("files/forAdding/$file"), ":");
            if ($date === "") {
                $date = date("Y-m-d H:i:s");
            }
            $model = file_info_model("files/forAdding/$file");
            $cords = get_coords("files/forAdding/$file");
            if ($cords != NULL) {
                $Latitude = strval($cords['GPSLatitude']);
                $Longitude = strval($cords['GPSLongitude']);
            }
            echo "photo/n";
            rotateImage("files/forAdding/$file", "files/full/$file");
            //resize("C:/wamp64/www/course1.1/files/full/$file", "C:/wamp64/www/course1.1/files/thumb/m_$file", '300', '', '');
            cropImage("files/forAdding/$file", "files/thumb/m_$file", 200, 200);


            if (!addFileToBd($file, "files/full/$file", "files/thumb/m_$file", $date, $bd, $model, $Latitude, $Longitude)) {
                echo "error";
            }
            unlink("files/forAdding/$file");
        }
        if (strripos($tipeFile, "video") !== false){
            exec("ffmpeg -i files/forAdding/$file files/temp/$file.jpeg");
            cropVidImage("files/temp/$file.jpeg", "files/thumb/m_$file.jpeg", 200, 200);
            addPlayLgo("files/thumb/m_$file.jpeg","play.png");
            if ($date === "") {
                $date = date("Y-m-d H:i:s");
            }
            if (!addFileToBd($file, "files/full/$file", "files/thumb/m_$file.jpeg", $date, $bd, $model, $Latitude, $Longitude)) {
                echo "error";
            }
            copy("files/forAdding/$file","files/full/$file");
            unlink("files/forAdding/$file");
            unlink("files/temp/$file.jpeg");
        }
    }
}

$bd-> close();