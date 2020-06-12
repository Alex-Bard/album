<?php
use PHPUnit\Framework\TestCase;
//require_once "bd.php";
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
    $result = $bd->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $sql = "DELETE FROM users  WHERE id='$id'";

        if ($bd->query($sql) === TRUE) {
            return 1;
        }
    }
}
class BDTest extends TestCase{
    private $vars, $bd;
    protected function setUp(){
        global $vars;
        $servername = "localhost";
        $username = "User";
        $password = "123456789";
        $dbname = "album";
// Create connection
        $bd = new mysqli($servername, $username, $password,$dbname);
        if ($bd->connect_error) {
            die("Connection failed: " . $bd->connect_error);
        }
        else{
            $this->vars = $vars;
            $this->bd = $bd;
        }
    }
    public function testBDcreate()
    {
        $this->assertEquals(2, createUser( $this->vars, $this->bd ));
    }
    public function testBDdelete()
    {
        $this->assertEquals(1, deleteUser($this->vars,$this->bd));
    }
}
