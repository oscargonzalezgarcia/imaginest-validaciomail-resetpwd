<?php

    require('./lib/control_registre.php');

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST['email']) && isset($_POST['user']) && isset($_POST['pwd']) && isset($_POST['vPwd']))
        {
            $userPOST = filter_input(INPUT_POST, 'user');
            $emailPOST = filter_input(INPUT_POST, 'email');
            isset($_POST['fname']) ? $fNamePOST = filter_input(INPUT_POST, 'fname') : $fNamePOST = null;
            isset($_POST['lname']) ? $lNamePOST = filter_input(INPUT_POST, 'lname') : $lNamePOST = null;
            $passPOST = filter_input(INPUT_POST, 'pwd');
            $passPOSTHashed = password_hash($passPOST,PASSWORD_DEFAULT);
            $vPwdPOST = filter_input(INPUT_POST, 'vPwd');
            $activationCode = hash(sha256,$userPOST);

            if(validaEmail($emailPOST)) $errEmail = "<p class='alert alert-danger'>Enter a valid email address!</p>";
            elseif($passPOST!=$vPwdPOST) $errPass = "<p class='alert alert-danger'>Passwords do not match!</p>";
            elseif(existeixUsuari($userPOST)) $errUser = "<p class='alert alert-danger'>This username already exists!</p>";
            elseif(existeixUsuari($emailPOST)) $errEmail = "<p class='alert alert-danger'>This email already exists!</p>";
            elseif(registrarUsuari($userPOST,$emailPOST,$fNamePOST,$lNamePOST,$passPOSTHashed,$activationCode))
            {
                setcookie("success","<p class='alert alert-success'>You have successfully registered! Please verify your email</p>",time()+3600);
                header("Location: index.php");
                enviaCorreuVerificacio($emailPOST,$activationCode);
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="initial-scale=1, maximum-scale=1">
        <title>ImagiNest</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="./css/estils.css" />
        <link rel="icon" href="./img/icon.jpg" />
    </head>
    <body>
        <div class="cont2">
            <div class="form">
                <form name="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <h1>ImagiNest</h1>
                    <input id="user" type="text" class="user" name="user" placeholder="Username" autofocus required/>
                    <?php if(isset($errUser) && !empty($errUser)) echo $errUser?>
                    <input id="email" type="email" class="user" name="email" placeholder="Email"required/>
                    <?php if(isset($errEmail) && !empty($errEmail)) echo $errEmail?>
                    <input id="fName" type="text" class="user" name="fname" placeholder="First name"/>
                    <input id="lName" type="text" class="user" name="lname" placeholder="Last name"/>
                    <input id="pwd" type="password" class="pass" name="pwd" required placeholder="Password"/>
                    <input id="vPwd" type="password" class="pass" name="vPwd" required placeholder="Verify password"/>
                    <?php if(isset($errPass) && !empty($errPass)) echo $errPass?>
                    <input class="login" type="submit" value="Sign up"/>
                </form>
            </div>
        </div>
    </body>
</html>