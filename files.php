<?php
/*if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if (isset($_SERVER['REQUEST_URI']))
        $vars = explode('/', $_SERVER['REQUEST_URI']);
    for ($i = 0; $i < count($list); $i++) {
        if (isset($vars[3]) && ($vars[3] == $list[$i]->id)) {
            array_splice($list, $i, 1);
            $notDeleted = false;
            header("HTTP/1.0 200 OK");
        }
    }
    if ($notDeleted)
        header("HTTP/1.0 406 Not Acceptable");
} elseif (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $vars = json_decode(file_get_contents("php://input"));
    if (isset($vars->text)){
        $floatingElement = 0;
        $insertFlag = false;
        foreach ($list as &$element) {
            if ($element->id - $floatingElement > 0) {
                $insertedElement = array($floatingElement => new ArrayElement($floatingElement, $vars->text, "x"));
                array_splice($list, $floatingElement, 0, $insertedElement);
                $insertFlag = true;
                break 1;
            } else {
                $floatingElement++;
            }
        }
        unset($element);
        if (!$insertFlag){
            array_push($list, new ArrayElement($floatingElement, $vars->text, "x"));
        }
        header("HTTP/1.0 200 OK");
    }
    else
        header("HTTP/1.0 400 Bad Request");
} elseif (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $vars = json_decode(file_get_contents("php://input"));
    if (isset($vars->id) && isset($vars->text)) {
        for ($i = 0; $i<count($list); $i++)
            if ($list[$i]->id == $vars->id) {
                $list[$i]->text = $vars->text;
                break 1;
            }
        header("HTTP/1.0 200 OK");
    }
    else
        header("HTTP/1.0 405 Invalid input");
}*/

require 'C:\wamp64\www\course\bd.php';
if(!isset($_COOKIE['session'])) {
    header('Location: login.html');
}
else {
    $token = $_COOKIE['session'];
    $sql = "SELECT  id, login,role, tokBot FROM users WHERE token='$token'";
    $result = mysqli_query($bd, $sql) or die (mysqli_error($bd));
    if (mysqli_num_rows($result) == 1) {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET['start'])) {
                $start = $_GET['start'];
                if ((($_GET['date1']) !== "") && (($_GET['date2']) !== "")) {
                    $date1 = $_GET['date1'];
                    $date2 = $_GET['date2'];
                    getFilesOnData($bd, $date1, $date2, $start);
                } else {
                    getFilesOnLimit($bd, $start);
                }
            } else {
                header("HTTP/1.0 400 Bead Request");
            }

        }
        elseif (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
            $vars = json_decode(file_get_contents("php://input"));
            if (isset ($vars)) {
                $varsLen = count($vars);
                for ( $i=0;$i<$varsLen;$i++) {
                    $delete =  deleteFile($vars[$i],$bd);
                    print_r($delete);
                    unlink($delete['orig']);
                    unlink($delete['miniature']);
                }
            }
        }
    }
}
$bd->close();
function getFilesOnLimit($bd, $start)
{
    $JsonString = "";
    $sql = "SELECT * FROM  files ORDER BY dateFile LIMIT " . $start . ",12";
    $result = mysqli_query($bd, $sql) or die (mysqli_error($bd));
    if (mysqli_num_rows($result) > 0) {
        header("HTTP/1.0 200 OK");
        header('Content-Type: application/json');
        //echo "[";
        $JsonString .= "[";
        while ($row = mysqli_fetch_assoc($result)) {
            //echo "{\"name\":\"". $row["name"]. "\",\"orig\":\"" . $row["orig"]. "\",\"miliature\":\"" . $row["miniature"]."\"},";
            $JsonString .= "{\"id\":\"" . $row["idFile"] . "\",\"name\":\"" . $row["nameFile"] . "\",\"date\":\"" . $row["dateFile"] . "\",\"orig\":\"" . $row["orig"] . "\",\"model\":\"" . $row["model"] . "\",\"miliature\":\"" . $row["miniature"] . "\",\"lat\":\"" . $row["lat"] . "\",\"clong\":\"" . $row["Clong"] . "\"},";
        }
        $JsonString = mb_substr($JsonString, 0, -1);
        //echo "]";
        $JsonString .= "]";
        print_r($JsonString);

    } else {
        header("HTTP/1.0 404 Not Found");
    }
}
function getFilesOnData($bd, $date1, $date2, $start)
{
    $JsonString = "";
    $sql = "SELECT * FROM  files where dateFile >= '$date1' and dateFile <= '$date2' LIMIT " . $start . ",12";
    $result = mysqli_query($bd, $sql) or die (mysqli_error($bd));
    if (mysqli_num_rows($result) > 0) {
        header("HTTP/1.0 200 OK");
        header('Content-Type: application/json');
        //echo "[";
        $JsonString .= "[";
        while ($row = mysqli_fetch_assoc($result)) {
            //echo "{\"name\":\"". $row["name"]. "\",\"orig\":\"" . $row["orig"]. "\",\"miliature\":\"" . $row["miniature"]."\"},";
            $JsonString .= "{\"id\":\"" . $row["idFile"] . "\",\"name\":\"" . $row["nameFile"] . "\",\"date\":\"" . $row["dateFile"] . "\",\"orig\":\"" . $row["orig"] . "\",\"model\":\"" . $row["model"] . "\",\"miliature\":\"" . $row["miniature"] . "\",\"lat\":\"" . $row["lat"] . "\",\"clong\":\"" . $row["Clong"] . "\"},";
        }
        $JsonString = mb_substr($JsonString, 0, -1);
        //echo "]";
        $JsonString .= "]";
        print_r($JsonString);

    } else {
        header("HTTP/1.0 404 Not Found");
    }
}
function deleteFile ($id,$bd){
    $sql = "SELECT orig, miniature FROM  files where idFile = '$id'";
    $result = mysqli_query($bd, $sql) or die (mysqli_error($bd));
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $sql = "DELETE FROM files  WHERE idFile='$id'";

        if ($bd->query($sql) === TRUE) {
            return $row;
        }
    }
}

?>