<?php
//namespace tests;
use PHPUnit\Framework\TestCase;
require_once "./login.php";
/*$vars = array(
    'login' => "TestUser123",
    'pass' => "123456789",
);*/
/*class vars{
    public $login = "123sdf2@#()";
    PUBLIC $pass = "123456789";
}*/
//$vars = new vars;
class LoginTest extends TestCase{
    private $vars, $bd;
    protected function setUp(){
        $vars = new vars;
        //global $vars;
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
    /*protected function tearDown(){
        $servername = "localhost";
        $username = "User";
        $password = "123456789";
        $dbname = "album";

        $bd = new mysqli($servername, $username, $password,$dbname);
        if ($bd->connect_error) {
            die("Connection failed: " . $bd->connect_error);
        }
        else{
            $sql = "SELECT  id FROM users WHERE login= TestUser123";
            $result = mysqli_query($bd, $sql) or die (mysqli_error($bd));
                $row = mysqli_fetch_assoc($result);
                $id = $row["id"];
            $bd = new mysqli($servername, $username, $password,$dbname);
            $sql = "DELETE FROM users  WHERE id = '$id'";
            if ($bd->query($sql) === FALSE) {
                echo "Error: " . $sql . "<br>" . $bd->error;
            }
        }
    }*/
    public function testunacceptableLogin()
    {
        $vars = new vars;
        // $this->assertEquals(1, regUser( $this->vars, $this->bd ));
        $this->assertEquals(0, loginUser( $vars, $this->bd ));
    }

    public function testIncorrectPassword()
    {
        $this->vars->login = "TestUser123";
        $this->vars->pass = "123";
        $this->assertEquals(0, loginUser($this->vars,$this->bd));
    }
    public function testEmptyLoginAndPass()
    {
        $this->vars->login = NULL;
        $this->vars->pass = NULL;
        $this->assertEquals(0, loginUser($this->vars,$this->bd));
    }
    public static function tearDownAfterClass()
    {
        global $vars;
        unset($vars);
    }
}

?>