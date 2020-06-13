<?php
require 'bd.php';
/**
 * @OA\Post(
 *     path="reg",
 *     summary="reg",
 *     operationId="reg",
 *     @OA\Parameter(
 *         name="login",
 *         in="query",
 *         @OA\Schema(
 *             type="string"
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="password",
 *         in="query",
 *         @OA\Schema(
 *             type="string"
 *         ),
 *     ),
 *
 *      @OA\Response(
 *          response=200,
 *           description="ok",
 *       ),
 *       @OA\Response(
 *            response=404,
 *            description="Not Found",
 *       ),
 *       @OA\Response(
 *          response=400,
 *           description="Bad Request",
 *       ),
 *  ),
 */
$result = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vars = json_decode(file_get_contents("php://input"));
    $result = regUser($vars,$bd);
    switch ($result){
        case 0:
            header("HTTP/1.0 404 Not Found");
            break;
        case 1:
            header("HTTP/1.0 403 Forbidden");
            break;
        case 2:
            header("HTTP/1.0 200 ok");
            break;

    }
    }
function regUser($vars,$bd){
    $login = $password = "";
    if (isset ($vars->login) &&(!preg_match( '/[^0-9a-zA-Z]/', $vars->login)) && isset($vars->pass)){
        $login = $vars->login;
        $password = password_hash($vars->pass, PASSWORD_DEFAULT);
        $token = md5(uniqid($login, true));
        $sql = "INSERT INTO users (login, pass, token)
VALUES ('$login', '$password', '$token')";
        if ($bd->query($sql) === TRUE) {
            setcookie('session',$token,time() + 86400 , "/");
          //  header("HTTP/1.0 200 ok");
            return 2;
        }
        else {
            /* echo "Error: " . $sql . "<br>" . $bd->error;*/
          //  header("HTTP/1.0 404 Not Found");
           // echo "Error: " . $sql . "<br>" . $bd->error;
           // header("HTTP/1.0 403 Forbidden");
            return 0;
        }
    }
    else return 1;

}
$bd->close();
?>