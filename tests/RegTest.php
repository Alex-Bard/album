
<?php
//namespace tests;
use PHPUnit\Framework\TestCase;
require_once "reg.php";
/*$vars = array(
    'login' => "TestUser123",
    'pass' => "123456789",
);*/
class vars{
    public $login = "123sdf2@#()";
    PUBLIC $pass = "123";
}
$vars = new vars;
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
    protected function tearDown(){
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
            $sql = "DELETE FROM users  WHERE login=TestUser123";
            $bd->query($sql);
        }
    }
    public function unacceptableLogin()
    {
        $this->assertEquals(1, regUser( $this->vars, $this->bd ));
    }
    public function reg()
    {
        $this->vars->login = "TestUser123";
        $this->vars->pass = "123456789";
        $this->assertEquals(2, regUser($this->vars,$this->bd));
    }
    public function repeatedReg()
    {
        $servername = "localhost";
        $username = "User";
        $password = "123456789";
        $dbname = "album";
// Create connection
        $bd = new mysqli($servername, $username, $password,$dbname);
        $sql = "INSERT INTO users (login, pass, token)
VALUES ('TestUser123', '123456789', '123456798')";
        $bd->query($sql);
        $this->assertEquals(0, regUser($this->vars,$this->bd));
    }
    public function emptyReg ()
    {
        $this->vars->login = "";
        $this->vars->pass = "";
        $this->assertEquals(1, regUser($this->vars,$this->bd));
    }
}

?>