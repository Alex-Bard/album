<?php


function resize($file_input, $file_output, $w_o, $h_o, $percent = false) {
    list($w_i, $h_i, $type,$ypa) = getimagesize($file_input);
    $n = 0;
    if (!$w_i || !$h_i) {
        echo 'Невозможно получить длину и ширину изображения при уменьшении';
        return;
    }
    $types = array('','gif','jpeg','png');
    $ext = $types[$type];
    if ($ext) {
        $func = 'imagecreatefrom'.$ext;
        $img = $func($file_input);
    } else {
        echo 'Некорректный формат файла';
        return;
    }
    if ($percent) {
        $w_o *= $w_i / 100;
        $h_o *= $h_i / 100;
    }
    if (!$h_o) $h_o = $w_o/($w_i/$h_i);
    if (!$w_o) $w_o = $h_o/($h_i/$w_i);
    $img_o = imagecreatetruecolor($w_o, $h_o);
    imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i);
    $exif = exif_read_data($file_input);
    if ($type == 2) {
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 1:
                    return imagejpeg(imagerotate($img_o, 0, 0),$file_output, 100);
                    break;
                case 3:
                    return imagejpeg(imagerotate($img_o, 180, 0),$file_output, 100);
                    break;

                case 6:
                    return  imagejpeg(imagerotate($img_o, -90, 0),$file_output, 100);
                    break;

                case 8:
                    return  imagejpeg(imagerotate($img_o, 90, 0),$file_output, 100);
                    break;
            }
        }
    } else {
        $func = 'image'.$ext;
        return  $func($img_o,$file_output);
    }
}

function cropImage($aInitialImageFilePath, $aNewImageFilePath, $aNewImageWidth, $aNewImageHeight) {
    if (($aNewImageWidth < 0) || ($aNewImageHeight < 0)) {
        return false;
    }
    $exif = exif_read_data($aInitialImageFilePath);
    // Массив с поддерживаемыми типами изображений
    $lAllowedExtensions = array(1 => "gif", 2 => "jpeg", 3 => "png");

    // Получаем размеры и тип изображения в виде числа
    list($lInitialImageWidth, $lInitialImageHeight, $lImageExtensionId) = getimagesize($aInitialImageFilePath);

    if (!array_key_exists($lImageExtensionId, $lAllowedExtensions)) {
        return false;
    }
    $lImageExtension = $lAllowedExtensions[$lImageExtensionId];

    // Получаем название функции, соответствующую типу, для создания изображения
    $func = 'imagecreatefrom' . $lImageExtension;
    // Создаём дескриптор исходного изображения
    $lInitialImageDescriptor = $func($aInitialImageFilePath);

    // Определяем отображаемую область
    $lCroppedImageWidth = 0;
    $lCroppedImageHeight = 0;
    $lInitialImageCroppingX = 0;
    $lInitialImageCroppingY = 0;
    if ($aNewImageWidth / $aNewImageHeight > $lInitialImageWidth / $lInitialImageHeight) {
        $lCroppedImageWidth = floor($lInitialImageWidth);
        $lCroppedImageHeight = floor($lInitialImageWidth * $aNewImageHeight / $aNewImageWidth);
        $lInitialImageCroppingY = floor(($lInitialImageHeight - $lCroppedImageHeight) / 2);
    } else {
        $lCroppedImageWidth = floor($lInitialImageHeight * $aNewImageWidth / $aNewImageHeight);
        $lCroppedImageHeight = floor($lInitialImageHeight);
        $lInitialImageCroppingX = floor(($lInitialImageWidth - $lCroppedImageWidth) / 2);
    }

    // Создаём дескриптор для выходного изображения
    $lNewImageDescriptor = imagecreatetruecolor($aNewImageWidth, $aNewImageHeight);
    imagecopyresampled($lNewImageDescriptor, $lInitialImageDescriptor, 0, 0, $lInitialImageCroppingX, $lInitialImageCroppingY, $aNewImageWidth, $aNewImageHeight, $lCroppedImageWidth, $lCroppedImageHeight);
    $func = 'image' . $lImageExtension;

    // сохраняем полученное изображение в указанный файл
    if (!empty($exif['Orientation'])) {
        switch ($exif['Orientation']) {
            case 1:
                return $func(imagerotate($lNewImageDescriptor,0,0), $aNewImageFilePath);
                break;
            case 3:
                return $func(imagerotate($lNewImageDescriptor,180,0), $aNewImageFilePath);
                break;

            case 6:
                return $func(imagerotate($lNewImageDescriptor,-90,0), $aNewImageFilePath);
                break;

            case 8:
                return $func(imagerotate($lNewImageDescriptor,90,0), $aNewImageFilePath);
                break;
        }
    }
}
function file_info_date($file){
    $date = "";
    $exif = exif_read_data($file, 'IFD0', true);
    if ($exif !== false) {
        foreach ($exif as $key => $section) {
            foreach ($section as $name => $val) {
                if (($key === "IFD0") && ($name === "DateTime")) {
                    $date = $val;
                }
                if (($key === "COMPUTED") && ($name === "DateTime")) {
                    $date = $val;
                }

            }
        }
        return $date;
    }
    return "";
}
function file_info_model($file){
    $model = "";
    $make= "";
    $exif = exif_read_data($file, 'IFD0', true);
    if ($exif !== false) {
        foreach ($exif as $key => $section) {
            foreach ($section as $name => $val) {
                if (($key === "IFD0") && ($name === "Make")) {
                    $make = $val;
                }
                if (($key === "IFD0") && ($name === "Model")) {
                    $model = $val;
                }

            }
        }

        $make .= " ";

        return $make .= $model;
    }
    return "не известно";

}
function file_info_coord($file){
    $GPSLatitude  = "";
    $GPSLongitude= "";
    $exif = exif_read_data($file, 'IFD0', true);
    foreach ($exif as $key => $section) {
        foreach ($section as $name => $val) {
            if (($key==="GPS") && ($name ==="GPSLatitude")){
                if ($val !=="") {
                    $GPSLatitude = $val;
                }
            }
            if (($key==="GPS") && ($name ==="GPSLongitude")){
                if ($val!=="") {
                    $GPSLongitude = $val;
                }
           }
             //echo "$key\n.$name \n: $val<br />\n";
        }
    }
     $lat = $GPSLatitude['0'] + $GPSLatitude['1']/60+$GPSLatitude['2']/3600;
    $long = $GPSLongitude['0'] +$GPSLongitude['1']/60+$GPSLongitude['2']/3600;
    $coords = strval($lat) ;
    $coords .='-';
    return $coords.=strval($GPSLongitude);
    //return $GPSLatitude;
}
function get_coords($file_path)
{
// Имя файла для обработки

// Массив с GPS-данными
    $gps_data = array();

    $f = fopen($file_path, 'r');
    $tmp = fread($f, 2);
    if ($tmp == chr(0xFF) . chr(0xD8)) {
        $section_id_stop = array(0xFFD8, 0xFFDB, 0xFFC4, 0xFFDD, 0xFFC0, 0xFFDA, 0xFFD9);
        while (!feof($f)) {
            $tmp = unpack('n', fread($f, 2));
            $section_id = $tmp[1];
            $tmp = unpack('n', fread($f, 2));
            $section_length = $tmp[1];

            // Началась секция данных, заканчиваем поиск
            if (in_array($section_id, $section_id_stop)) {
                break;
            }

            // Найдена EXIF-секция
            if ($section_id == 0xFFE1) {
                $exif = fread($f, ($section_length - 2));

                // Это действительно секция EXIF?
                if (substr($exif, 0, 4) == 'Exif') {
                    // Определить порядок следования байт
                    switch (substr($exif, 6, 2)) {
                        case 'MM':
                        {
                            $is_motorola = true;
                            $mask1 = 'n';
                            $mask2 = 'N';
                            break;
                        }
                        case 'II':
                        {
                            $is_motorola = false;
                            $mask1 = 'v';
                            $mask2 = 'V';
                            break;
                        }
                    }
                    // Количество тегов
                    $tmp = unpack($mask2, substr($exif, 10, 4));
                    $offset_tags = $tmp[1];
                    $tmp = unpack($mask1, substr($exif, 14, 2));
                    $num_of_tags = $tmp[1];

                    if ($num_of_tags == 0) {
                        return true;
                    }

                    $offset = $offset_tags + 8;

                    // Поискать тег GPSInfo
                    for ($i = 0; $i < $num_of_tags; $i++) {
                        $tmp = unpack($mask1, substr($exif, $offset, 2));
                        $tag_id = $tmp[1];
                        $tmp = unpack($mask2, substr($exif, $offset + 8, 4));
                        $value = $tmp[1];

                        $offset += 12;

                        // GPSInfo
                        if ($tag_id == 0x8825) {
                            $gps_offset = $value + 6;
                            // Количество GPS-тегов
                            $tmp = unpack($mask1, substr($exif, $gps_offset, 2));
                            $num_of_gps_tags = $tmp[1];

                            $offset = $gps_offset + 2;

                            if ($num_of_gps_tags > 0) {
                                // Обработка GPS-тегов
                                for ($i = 0; $i < $num_of_gps_tags; $i++) {
                                    $tmp = unpack($mask1, substr($exif, $offset, 2));
                                    $tag_id = $tmp[1];
                                    $tmp = unpack($mask2, substr($exif, $offset + 8, 4));
                                    $value = $tmp[1];

                                    // GPSLatitudeRef или GPSLongitudeRef
                                    if ($tag_id == 0x0001 || $tag_id == 0x0003) {
                                        $tmp = unpack('V', substr($exif, $offset + 8, 4));
                                        $value = $tmp[1];
                                        if ($value != 0) {
                                            if ($tag_id == 0x0001) {
                                                $gps_data['GPSLatitudeRef'] = chr($value);
                                            } else {
                                                $gps_data['GPSLongitudeRef'] = chr($value);
                                            }
                                        }
                                    }
                                    // GPSLatitude или GPSLongitude
                                    if ($tag_id == 0x0002 || $tag_id == 0x0004) {
                                        $rational_offset = $value + 6;
                                        $tmp = unpack($mask2, substr($exif, $rational_offset + 4 * 0, 4));
                                        $val1 = $tmp[1];
                                        $tmp = unpack($mask2, substr($exif, $rational_offset + 4 * 1, 4));
                                        $div1 = $tmp[1];
                                        $tmp = unpack($mask2, substr($exif, $rational_offset + 4 * 2, 4));
                                        $val2 = $tmp[1];
                                        $tmp = unpack($mask2, substr($exif, $rational_offset + 4 * 3, 4));
                                        $div2 = $tmp[1];
                                        $tmp = unpack($mask2, substr($exif, $rational_offset + 4 * 4, 4));
                                        $val3 = $tmp[1];
                                        $tmp = unpack($mask2, substr($exif, $rational_offset + 4 * 5, 4));
                                        $div3 = $tmp[1];
                                        if ($div1 != 0 && $div2 != 0 && $div3 != 0) {
                                            $tmp = round(($val1 / $div1 + $val2 / $div2 / 60 + $val3 / $div3 / 3600), 6);
                                            if ($tag_id == 0x0002) {
                                                $gps_data['GPSLatitude'] = $tmp;
                                            } else {
                                                $gps_data['GPSLongitude'] = $tmp;
                                            }
                                        }
                                    }
                                    // GPSSatellites
                                    if ($tag_id == 0x0008) {
                                        $tmp = intval(substr($exif, $offset + 8, 4));
                                        if ($tmp > 0) {
                                            $gps_data['GPSSatellites'] = $tmp;
                                        }
                                    }

                                    $offset += 12;
                                }
                            }
                            break;
                        }
                    }
                }
            } else {
                // Пропустить секцию
                fseek($f, ($section_length - 2), SEEK_CUR);
            }
            // Тег GPSInfo найден
            if (count($gps_data) != 0) {
                break;
            }
        }
    }
    fclose($f);

// Данные GPS
return $gps_data;
}
function rotateImage ($aInitialImageFilePath, $aNewImageFilePath){
    $exif = exif_read_data($aInitialImageFilePath);
    // Массив с поддерживаемыми типами изображений
    $lAllowedExtensions = array(1 => "gif", 2 => "jpeg", 3 => "png");

    // Получаем размеры и тип изображения в виде числа
    list($lInitialImageWidth, $lInitialImageHeight, $lImageExtensionId) = getimagesize($aInitialImageFilePath);

    if (!array_key_exists($lImageExtensionId, $lAllowedExtensions)) {
        return false;
    }
    $lImageExtension = $lAllowedExtensions[$lImageExtensionId];

    // Получаем название функции, соответствующую типу, для создания изображения
    $func = 'imagecreatefrom' . $lImageExtension;
    // Создаём дескриптор исходного изображения
    $lInitialImageDescriptor = $func($aInitialImageFilePath);
    $func = 'image' . $lImageExtension;
    // сохраняем полученное изображение в указанный файл
    if (!empty($exif['Orientation'])) {
        switch ($exif['Orientation']) {
            case 1:
                return $func(imagerotate($lInitialImageDescriptor,0,0), $aNewImageFilePath);
                break;
            case 3:
                return $func(imagerotate($lInitialImageDescriptor,180,0), $aNewImageFilePath);
                break;

            case 6:
                return $func(imagerotate($lInitialImageDescriptor,-90,0), $aNewImageFilePath);
                break;

            case 8:
                return $func(imagerotate($lInitialImageDescriptor,90,0), $aNewImageFilePath);
                break;
        }
    }
}

function cropVidImage($aInitialImageFilePath, $aNewImageFilePath, $aNewImageWidth, $aNewImageHeight) {
    if (($aNewImageWidth < 0) || ($aNewImageHeight < 0)) {
        return false;
    }

    $lAllowedExtensions = array(1 => "gif", 2 => "jpeg", 3 => "png");

    // Получаем размеры и тип изображения в виде числа
    list($lInitialImageWidth, $lInitialImageHeight, $lImageExtensionId) = getimagesize($aInitialImageFilePath);

    if (!array_key_exists($lImageExtensionId, $lAllowedExtensions)) {
        return false;
    }
    $lImageExtension = $lAllowedExtensions[$lImageExtensionId];

    // Получаем название функции, соответствующую типу, для создания изображения
    $func = 'imagecreatefrom' . $lImageExtension;
    // Создаём дескриптор исходного изображения
    $lInitialImageDescriptor = $func($aInitialImageFilePath);

    // Определяем отображаемую область
    $lCroppedImageWidth = 0;
    $lCroppedImageHeight = 0;
    $lInitialImageCroppingX = 0;
    $lInitialImageCroppingY = 0;
    if ($aNewImageWidth / $aNewImageHeight > $lInitialImageWidth / $lInitialImageHeight) {
        $lCroppedImageWidth = floor($lInitialImageWidth);
        $lCroppedImageHeight = floor($lInitialImageWidth * $aNewImageHeight / $aNewImageWidth);
        $lInitialImageCroppingY = floor(($lInitialImageHeight - $lCroppedImageHeight) / 2);
    } else {
        $lCroppedImageWidth = floor($lInitialImageHeight * $aNewImageWidth / $aNewImageHeight);
        $lCroppedImageHeight = floor($lInitialImageHeight);
        $lInitialImageCroppingX = floor(($lInitialImageWidth - $lCroppedImageWidth) / 2);
    }

    // Создаём дескриптор для выходного изображения
    $lNewImageDescriptor = imagecreatetruecolor($aNewImageWidth, $aNewImageHeight);
    imagecopyresampled($lNewImageDescriptor, $lInitialImageDescriptor, 0, 0, $lInitialImageCroppingX, $lInitialImageCroppingY, $aNewImageWidth, $aNewImageHeight, $lCroppedImageWidth, $lCroppedImageHeight);
    $func = 'image' . $lImageExtension;

    // сохраняем полученное изображение в указанный файл

                return $func(imagerotate($lNewImageDescriptor,0,0), $aNewImageFilePath);
}
//cropVidImage("files/temp/$file.jpeg", "files/thumb/m_$file.jpeg", 200, 200)
function addPlayLgo($img,$logo) {
// imagecreatefrompng - создаёт новое изображение из файла или URL
// водяной знак
    $wm=imagecreatefrompng($logo);

// imagesx - получает ширину изображения
    $wmW=imagesx($wm);

// imagesy - получает высоту изображения
    $wmH=imagesy($wm);


    $image=imagecreatetruecolor($wmW, $wmH);


    if(preg_match("/.gif/i",$img)):
        $image=imagecreatefromgif($img);
    elseif(preg_match("/.jpeg/i",$img) or preg_match("/.jpg/i",$img)):
        $image=imagecreatefromjpeg($img);
    elseif(preg_match("/.png/i",$img)):
        $image=imagecreatefrompng($img);
    else:
        die("Ошибка! Неизвестное расширение изображения");
    endif;

    $size=getimagesize($img);


    $cx=$size[0]-$wmW;
    $cy=$size[1]-$wmH;


    imagecopyresampled ($image, $wm, $cx, $cy, 0, 0, $wmW, $wmH, $wmW, $wmH);


    imagejpeg($image,$img,100);

// imagedestroy - освобождает память
    imagedestroy($image);

    imagedestroy($wm);

// на всякий случай
    unset($image,$img);
}
?>
