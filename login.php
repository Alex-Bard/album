<?php
require 'bd.php';
$result = 0;
/**
 * @OA\Post(
 *     path="/login",
 *     summary="login",
 *     operationId="login",
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
 *            response=403,
 *            description="Forbidden",
 *       ),
 *       @OA\Response(
 *          response=400,
 *           description="Bad Request",
 *       ),
 *       @OA\Response(
 *          response=404,
 *           description="user not found",
 *       ),
 *  ),
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vars = json_decode(file_get_contents("php://input"));
    $result = loginUser($vars,$bd);
    switch ($result){
        case 0:
            header("HTTP/1.0 403 Forbidden");
            break;
        case 1:
            header("HTTP/1.0 404 ");
            break;
        case 2:
            header("HTTP/1.0 200 ok");
            break;

    }
}
 /* Выполняет вход пользователя в систму. В случае удачи возвращает 2, 0 - в случае неверного логина или пароля, 1 - в случае ошибки*/
function loginUser($vars,$bd){
    $login = $password = "";
    if (isset ($vars->login) &&(!preg_match( '/[^0-9a-zA-Z]/', $vars->login)) && isset($vars->pass)){
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
                    header('Location: index.html');
                    return 2;
                } else {
                    echo "Error updating record: " . $bd->error;
                    return 1;
                }
            } else {
                //header("HTTP/1.0 403 Forbidden");
                $Err = "Неверный пароль";
                //echo "нечеоынй пародб";
                return 0;
            }
        }
    }
    else{return 0;}
}
$bd->close();
?>