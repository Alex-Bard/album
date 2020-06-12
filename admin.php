<?php
require 'bd.php';
if(!isset($_COOKIE['session'])) {
    header('Location: login.html');
}
else {
    $token = $_COOKIE['session'];
    $sql = "SELECT  id, login,role, tokBot FROM users WHERE token='$token'";
    $result = mysqli_query($bd, $sql) or die (mysqli_error($bd));
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $JsonString = "";
        /*если ок*/
        $login = $row["login"];
        $role = $row["role"];
        $tokBot = $row["tokBot"];
        $id = $row["id"];
        /**
         * @OA\Get(
         *     path="/admin",
         *     summary="getUsers",
         *     operationId="getUsers",
         *     @OA\Response(
         *         response=200,
         *         description="ok",
         *         @OA\Schema(
         *             type="object",
         *             @OA\properties(
         *                  @OA\id(
         *                     type="integer",
         *                     description="userId",
         *                   ),
         *                  @OA\role(
         *                     type="string",
         *                   ),
         *                  @OA\login(
         *                     type="integer",
         *                   ),
         *                  @OA\tokBot(
         *                     type="string",
         *                   ),
         *               ),
         *           ),
         *         ),
         *),
         */
         if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
            $JsonString = "[{\"id\":\"" . $id . "\",\"login\":\"" . $login . "\",\"role\":\"" . $role . "\",\"tokbot\":\"" . $tokBot . "\"},";
            $sql = "SELECT id,login,role FROM  users where  id != '$id'";
            $result = mysqli_query($bd, $sql) or die (mysqli_error($bd));
            if (mysqli_num_rows($result) > 0) {
                header("HTTP/1.0 200 OK");
                header('Content-Type: application/json');
                //echo "[";
                while ($row = mysqli_fetch_assoc($result)) {
                    //echo "{\"name\":\"". $row["name"]. "\",\"orig\":\"" . $row["orig"]. "\",\"miliature\":\"" . $row["miniature"]."\"},";
                    $JsonString .= "{\"id\":\"" . $row["id"] . "\",\"login\":\"" . $row["login"] . "\",\"role\":\"" . $row["role"] . "\"},";
                }
                $JsonString = mb_substr($JsonString, 0, -1);
                //echo "]";
                $JsonString .= "]";
                print_r($JsonString);
            }
            /* echo "Cookie named '" . $login . "' is not set!";
         } else {
             echo "Cookie '" . 'session' . "' is set!<br>";
             echo "Value is: " . $_COOKIE['session'];*/
        }
         /**
          * @OA\Put(
          *     path="/admin",
          *     summary="delete_user",
          *     operationId="delete_user",
          *     @OA\Parameter(
          *         name="userId",
          *         in="query",
          *         @OA\Schema(
          *             type="integer"
          *         ),
          *     ),
          *      @OA\Response(
          *          response=200,
          *           description="ok",
          *       ),
          *       @OA\Response(
          *            response=404,
          *            description="User Not Found",
          *       ),
          *       @OA\Response(
          *          response=400,
          *           description="Bad Request",
          *       ),
          *  ),
          */
         elseif (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PUT') {
             $idDelete = file_get_contents("php://input");
             print_r($idDelete);
             if ($role == 2) {
                 $sql = "DELETE FROM users  WHERE id='$idDelete'";

                 if ($bd->query($sql) === TRUE) {
                     header("HTTP/1.0 200 OK");
                 } else {
                     header("HTTP/1.0 404 OK");
                 }
             } else {
                 header("HTTP/1.0 403 OK");
             }
         }
         /**
          * @OA\Post(
          *     path="/admin",
          *     summary="role_user",
          *     operationId="role_user",
          *     @OA\Parameter(
          *         name="userId",
          *         in="query",
          *         @OA\Schema(
          *             type="integer"
          *         ),
          *     ),
          *     @OA\Parameter(
          *         name="userRole",
          *         in="query",
          *         @OA\Schema(
          *             type="integer"
          *         ),
          *     ),
          *      @OA\Response(
          *          response=200,
          *           description="ok",
          *       ),
          *       @OA\Response(
          *            response=404,
          *            description="User Not Found",
          *       ),
          *       @OA\Response(
          *          response=400,
          *           description="Bad Request",
          *       ),
          *       @OA\Response(
          *          response=403,
          *           description="Forbidden",
          *       ),
          *  ),
          */

         elseif (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
             $idRole = file_get_contents("php://input");
             if ($role== 2){
                 $setRole = 1;
                 $sql = "SELECT role from users WHERE id='$idRole'";
                 $result = mysqli_query($bd, $sql) or die (mysqli_error($bd));
                 $row = mysqli_fetch_assoc($result);
                 $RoleUser = $row['role'];
                 if($setRole == 1){$setRole = 2;}
                 if($RoleUser== 2) {$setRole = 1;}
                 $sql = "UPDATE users SET role ='$setRole' WHERE id='$idRole'";
                 if ($bd->query($sql) === TRUE) {
                     header("HTTP/1.0 200 OK");
                 } else {
                     header("HTTP/1.0 404 OK");
                 }
             }
             else {
                 header("HTTP/1.0 403 OK");
             }
    }
    else {
        header('Location: login.php');
    }
    }
}
$bd->close();?>
