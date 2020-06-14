<?php
/**
 * @OA\Info(
 *   title=" family album",
 *   version="1.0.0",
 * )
 */
require './bd.php';
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
/**
 * @OA\Get(
 *     path="/files",
 *     summary="getFiles",
 *     operationId="getFiles",
 *     @OA\Parameter(
 *         name="date",
 *         in="query",
 *         @OA\Schema(
 *             type="integer",
 *         ),
 *     ),
 *     @OA\Parameter(
 *         name="start",
 *         in="query",
 *         @OA\Schema(
 *             type="integer",
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="ok",
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="miliature_ref", type="string"),
 *             @OA\Property(property="fileName", type="string"),
 *             @OA\Property(property="original_ref", type="string"),
 *             @OA\Property(property="date", type="string"),
 *             @OA\Property(property="GPSLatitude", type="string"),
 *             @OA\Property(property="GPSLongitude", type="string"),
 *             @OA\Property(property="model", type="string"),
 *                   ),
 *               ),
 *         @OA\Response(
 *            response=400,
 *            description="Bad Request",
 *         ),
 *         @OA\Response(
 *            response=403,
 *            description="Forbidden",
 *     ),
 *),
 */
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

            $JsonString .= "{\"id\":\"" . $row["idFile"] . "\",\"name\":\"" . $row["nameFile"] . "\",\"date\":\"" . $row["dateFile"] . "\",\"orig\":\"" . $row["orig"] . "\",\"model\":\"" . $row["model"] . "\",\"miliature\":\"" . $row["miniature"] . "\",\"lat\":\"" . $row["lat"] . "\",\"clong\":\"" . $row["Clong"] . "\"},";
        }
        $JsonString = mb_substr($JsonString, 0, -1);

        $JsonString .= "]";
        print_r($JsonString);

    } else {
        header("HTTP/1.0 404 Not Found");
    }
}
/**
 * @OA\Delete(
 *     path="/files",
 *     summary="deleteFile",
 *     operationId="deleteFile",
 *     @OA\Parameter(
 *         name="fileId",
 *         in="query",
 *         @OA\Schema(
 *             type="integer"
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
 *  ),
 */
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