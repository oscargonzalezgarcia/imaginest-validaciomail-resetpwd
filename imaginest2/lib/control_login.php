<?php

    include_once('consultes_db.php');
    use PHPMailer\PHPMailer\PHPMailer;

    function verificaUsuari($user,$pass)
    {
        return isPasswordCorrect($user,$pass);
    }

    function existeixUsuariActiu($user)
    {   
        return (getCountActiveUser($user)==1 ? true : false);
    }

    function actualitzaLastLogin($user)
    {
        updateUser($user);        
    }

    function nomUsuari($user)
    {
        return getUserName($user);
    }

    function email($user)
    {
        return getEmail($user);
    }

    function actualitzaUsuari($resetPassCode,$userPOST)
    {
        updateUserPass($resetPassCode,$userPOST);
    }

    function resetPassCode($mail)
    {
        return getResetCode($mail);
    }

    function comprovaExpiryTime($mail)
    {
        return getExpiryTime($mail);
    }

    function abortarResetPassword($mail)
    {
        nullResetPassword($mail);
    }

    function actualitzaPassword($pass,$mail)
    {
        updatePass($pass,$mail);
        nullResetPassword($mail);
    }

    function enviaCorreuExit($email)
    {
        require 'vendor/autoload.php';
        $mail = new PHPMailer();
        $mail->IsSMTP();
    
        //Configuració del servidor de Correu
        //Modificar a 0 per eliminar msg error
        $mail->SMTPDebug = 2;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        
        //Credencials del compte GMAIL
        $mail->Username = '';
        $mail->Password = '';
    
        //Dades del correu electrònic
        $mail->SetFrom('','Imaginest');
        $mail->Subject = 'Imaginest Password Reset';
        
        $html = "
            <!DOCTYPE html>
                <html>
                    <head>
                        <meta charset='UTF-8'/>
                        <meta name='viewport' content='initial-scale=1, maximum-scale=1'>
                        <title>ImagiNest</title>
                        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' integrity='sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm' crossorigin='anonymous'>
                        <style>
                            * {text-align: center; background-color: #c8bec2; color: black}
                        </style>
                    </head>
                    <body>
                        <div>
                            <hr/>
                            <h1>ImagiNest</h1>
                            <h3>Your password has been changed succesfully!</h3>
                            <hr/>
                        </div>
                    </body>
                </html>
        ";
        $mail->MsgHTML($html);
        
        //Destinatari.
        $address = $email;
        $mail->AddAddress($address, 'User');
    
        //Enviament
        $result = $mail->Send();
        if(!$result) echo 'Error: ' . $mail->ErrorInfo;
    }
    
    function enviaCorreuReset($resetPassCode,$userPOST)
    {
        require 'vendor/autoload.php';
        $mail = new PHPMailer();
        $mail->IsSMTP();
    
        //Configuració del servidor de Correu
        //Modificar a 0 per eliminar msg error
        $mail->SMTPDebug = 2;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        
        //Credencials del compte GMAIL
        $mail->Username = '';
        $mail->Password = '';
    
        //Dades del correu electrònic
        $mail->SetFrom('','Imaginest');
        $mail->Subject = 'Imaginest Password Reset';

        if(filter_var($userPOST, FILTER_VALIDATE_EMAIL)) $anchor = "<a href='http://localhost/imaginest2/resetPassword.php?code=$resetPassCode&mail=$userPOST'>Reset Password</a>";
        else $anchor = "<a href='http://localhost/imaginest2/resetPassword.php?code=$resetPassCode&user=$userPOST'>Reset Password</a>";
        
        $html = "
            <!DOCTYPE html>
                <html>
                    <head>
                        <meta charset='UTF-8'/>
                        <meta name='viewport' content='initial-scale=1, maximum-scale=1'>
                        <title>ImagiNest</title>
                        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' integrity='sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm' crossorigin='anonymous'>
                        <style>
                            * {text-align: center; background-color: #c8bec2; color: black}
                        </style>
                    </head>
                    <body>
                        <div>
                            <hr/>
                            <h1>ImagiNest</h1>
                            <h3>You have 30 minutes to reset the password!</h3>
                            $anchor
                            <hr/>
                        </div>
                    </body>
                </html>
        ";
        $mail->MsgHTML($html);
        
        //Destinatari.
        $address = email($userPOST);
        $mail->AddAddress($address, 'User');
    
        //Enviament
        $result = $mail->Send();
        if(!$result) echo 'Error: ' . $mail->ErrorInfo;
    }