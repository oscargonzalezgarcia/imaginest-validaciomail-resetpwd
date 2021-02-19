<?php

    require('./lib/control_login.php');
       
    if($_SERVER["REQUEST_METHOD"] == "GET")
    {
        if(isset($_GET['code']) && (isset($_GET['user']) || isset($_GET['mail'])))
        {
            if(isset($_GET['user'])) $mail = email($_GET['user']);
            else $mail = $_GET['mail'];

            setcookie("mail",$mail,time()+3600);
            
            $resetPassCode = resetPassCode($mail);
            $mailExpiryTime = comprovaExpiryTime($mail);
            if($_GET['code']!=$resetPassCode || $mailExpiryTime==0)  
            {
                abortarResetPassword($mail);
                header("Location: index.php");
            }
        }
        else header("Location: index.php");
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST['pwd']) && isset($_POST['vpwd']))
        {
            $passPOST = filter_input(INPUT_POST, 'pwd');
            $passPOSTHashed = password_hash($passPOST,PASSWORD_DEFAULT);
            $vPwdPOST = filter_input(INPUT_POST, 'vpwd');
            if($passPOST==$vPwdPOST)
            {
                setcookie("successPwd","<p class='alert alert-success'>You have successfully reset the password!</p>",time()+3600);
                header("Location: index.php");
                actualitzaPassword($passPOSTHashed,$_COOKIE['mail']);
                enviaCorreuExit($_COOKIE['mail']);
                setcookie("mail","",time()-3600);
            }
            else $errPass = "<p class='alert alert-danger'>Passwords do not match!</p>";
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
        <div class="cont">
            <div class="form">
                <form name="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <h1>ImagiNest</h1>
                    <?php if(isset($errPass)) echo $errPass?>
                    <input id="pwd" type="password" class="pass" name="pwd" placeholder="Password" required/>
                    <input id="vpwd" type="password" class="pass" name="vpwd" placeholder="Verify Password" required/>
                    <input class="login" type="submit" value="Reset Password"/>
                </form>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>