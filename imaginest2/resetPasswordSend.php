<?php

    require('./lib/control_registre.php');
    require('./lib/control_login.php');
       
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST['user']))
        {
            $userPOST = filter_input(INPUT_POST, 'user');
            $resetPassCode = hash('sha256',$userPOST);

            if(existeixUsuari($userPOST))
            {
                header("Location: index.php");
                actualitzaUsuari($resetPassCode,$userPOST);
                enviaCorreuReset($resetPassCode,$userPOST);
            }
        }
    }

?>