
<?php



// Каталог, в который мы будем принимать файл:
$uploaddir = 'files/forAdding/';
$uploadfile = $uploaddir.basename($_FILES['file']['name']);

// Копируем файл из каталога для временного хранения файлов:
if (copy($_FILES['file']['tmp_name'], $uploadfile))
{
    require 'add.php';
echo "<h3>Файл успешно загружен на сервер</h3>";
}
else { echo "<h3>Ошибка! Не удалось загрузить файл на сервер!</h3>"; exit; }

// Выводим информацию о загруженном файле:
echo "<h3>Информация о загруженном на сервер файле: </h3>";
echo "<p><b>Оригинальное имя загруженного файла: ".$_FILES['file']['name']."</b></p>";
echo "<p><b>Mime-тип загруженного файла: ".$_FILES['file']['type']."</b></p>";
echo "<p><b>Размер загруженного файла в байтах: ".$_FILES['file']['size']."</b></p>";
echo "<p><b>Временное имя файла: ".$_FILES['file']['tmp_name']."</b></p>";



?>
