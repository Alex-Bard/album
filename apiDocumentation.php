<?php
require("vendor/autoload.php");
$openapi = \openapi\scan('C:\wamp64\www\course');
header('Content-Type: application/x-yaml');
echo $openapi->toYaml();
?>