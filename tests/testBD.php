<?php
use PHPUnit\Framework\TestCase;
//require_once "bd.php";
require_once (realpath('bd.php'));
$vars = array(
    'login' => "123123",
    'pass' => "123",
);
function createUser($vars,$bd){
    $login = $password = "";
    if (isset ($vars->login) && isset($vars->pass)){
        $login = $vars->login;
        $password = password_hash($vars->pass, PASSWORD_DEFAULT);
        $token = md5(uniqid($login, true));
        $sql = "INSERT INTO users (login, pass, token)
VALUES ('$login', '$password', '$token')";
        if ($bd->query($sql) === TRUE) {
            //  header("HTTP/1.0 200 ok");
            return 2;
        }
        else {
            return 0;
        }
    }
    else return 1;

}
function DeleteUser($bd){
    $sql = "SELECT  id FROM users WHERE login='$vars->login'";
    $result = mysqli_query($bd, $sql) or die (mysqli_error($bd));
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $sql = "DELETE FROM users  WHERE id='$row'";

        if ($bd->query($sql) === TRUE) {
            return 1;
        }
    }
}
class BDTest extends TestCase{
    public function testBDcreate()
    {
        $this->assertEquals(2, loginUser($GLOBALS["vars"],$GLOBALS["bd"]));
    }
    public function testBDdelete()
    {
        $this->assertEquals(1, loginUser($GLOBALS["bd"]));
    }
}