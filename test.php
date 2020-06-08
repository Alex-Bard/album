<?php
use PHPUnit\Framework\TestCase;
//require_once "bd.php";
require_once'bd.php';
$vars = array(
    'login' => "123123",
    'pass' => "123",
);
function createUser($vars,$bd){
    $login = $password = "";
    if (isset ($vars['login']) && isset($vars['pass'])){
        $login = $vars['login'];
        $password = password_hash($vars['pass'], PASSWORD_DEFAULT);
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
function DeleteUser( $vars,$bd){
    $login = $vars['login'];
    $sql = "SELECT  id FROM users WHERE login='$login'";
    $result = mysqli_query($bd, $sql) or die (mysqli_error($bd));
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id = $row['id'];
        $sql = "DELETE FROM users  WHERE id='$id'";

        if ($bd->query($sql) === TRUE) {
            return 1;
        }
    }
}
class BDTest extends TestCase{
    public function testBDcreate()
    {
        $vars = $GLOBALS['vars'];
        $bd = $GLOBALS['bd'];
        $this->assertEquals(2, createUser( $vars, $bd ));
    }
    public function testBDdelete()
    {
        $vars = $GLOBALS['vars'];
        $bd = $GLOBALS['bd'];
        $this->assertEquals(1, deleteUser($vars,$bd));
    }
}