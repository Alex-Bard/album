<?php
require 'bd.php';
$login = $password = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vars = json_decode(file_get_contents("php://input"));
    if (isset ($vars->login) && isset($vars->pass)){
        $login = $vars->login;
        $password = password_hash($vars->pass, PASSWORD_DEFAULT);
        $token = md5(uniqid($login, true));
        $sql = "INSERT INTO users (login, pass, token)
VALUES ('$login', '$password', '$token')";
        if ($bd->query($sql) === TRUE) {
            setcookie('session',$token,time() + 86400 , "/");
            header("HTTP/1.0 200 ok");
        } else {
            /* echo "Error: " . $sql . "<br>" . $bd->error;*/
            header("HTTP/1.0 404 Not Found");
            echo "Error: " . $sql . "<br>" . $bd->error;
            header("HTTP/1.0 403 Forbidden");
        }
    }
    }

$bd->close();
?>