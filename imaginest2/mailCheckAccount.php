<?php

    require('./lib/control_registre.php');

    if($_SERVER["REQUEST_METHOD"] == "GET")
    {
        activaUsuari($_GET['code'],$_GET['mail']);
        header("Location: index.php");
    }
?>