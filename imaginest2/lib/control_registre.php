<?php

    include_once('consultes_db.php');
    use PHPMailer\PHPMailer\PHPMailer;

    function existeixUsuari($user)
    {   
        return (getCountUser($user)==1 ? true : false);
    }

    function validaEmail($email)
    {
        return (filter_var($email, FILTER_VALIDATE_EMAIL) ? false : true);
    }

    function registrarUsuari($userPOST,$emailPOST,$fNamePOST,$lNamePOST,$passPOST,$activationCode)
    {
        return insertUser($userPOST,$emailPOST,$fNamePOST,$lNamePOST,$passPOST,$activationCode);
    }

    function activaUsuari($code,$email)
    {
        $activationCode = getActivationCode($email);
        if($activationCode==$code) activateUser($email);
    }

    function enviaCorreuVerificacio($email,$activationCode)
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
        $mail->Subject = 'Imaginest Email Verification';
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
                            <h2>Verify your email</h2>
                            <a href='http://localhost/imaginest2/mailCheckAccount.php?code=$activationCode&mail=$email'>Verify</a>
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