<?php
setcookie('session', 's', time() - 86400, "/");
header('Location: login.html');
?>