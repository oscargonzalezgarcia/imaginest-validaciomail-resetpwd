<?php
    if(!isset($_COOKIE[session_name()])){
        header("Location: index.php");
        exit;
    }
    else{
        session_start();
        if(!isset($_SESSION['usuari'])){
            header("Location: logout.php");
            exit;
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
                <h1>Welcome <?php echo "$_SESSION[usuari]"; ?></h1>
                <form name="form" action="<?php echo htmlspecialchars('logout.php');?>" method="POST">
                    <input class="login" type="submit" value="Log out"/>
                </form>
            </div>
        </div>
    </body>
</html>