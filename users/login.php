<?php
require 'bdusers.php';
$login = $password = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vars = json_decode(file_get_contents("php://input"));
    if (isset ($vars->login) && isset($vars->pass)) {
        $login = $vars->login;
        $password = $vars->pass;;
        $token = md5(uniqid($login, true));
        $sql = "SELECT  id, pass FROM users WHERE login='$login'";
        $result = mysqli_query($bd, $sql) or die (mysqli_error($bd));
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            print_r($row);
            if (password_verify($password, $row["pass"])) {
                $id = $row['id'];
                $token = md5(uniqid($login, true));
                $sql = "UPDATE users SET token ='$token' WHERE id='$id'";
                if ($bd->query($sql) === TRUE) {
                    setcookie('session', $token, time() + 86400, "/");
                    header('Location: index.php');
                } else {
                    echo "Error updating record: " . $bd->error;
                }
            } else {
                header("HTTP/1.0 403 Forbidden");
                $Err = "Неверный пароль";
                //echo "нечеоынй пародб";
            }
        }
    }
}
?>