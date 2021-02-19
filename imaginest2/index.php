<?php

    require_once("./lib/control_login.php");
       
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST['user']) && isset($_POST['pwd']))
        {
            $userPOST = filter_input(INPUT_POST, 'user');
            $passPOST = filter_input(INPUT_POST, 'pwd');

            if(existeixUsuariActiu($userPOST))
            {
                $usuari = verificaUsuari($userPOST,$passPOST);
                if($usuari){
                    session_start();
                    $_SESSION['usuari'] = nomUsuari($userPOST);
                    actualitzaLastLogin($userPOST);
                    header("Location: home.php");
                    exit;
                }
                else $errUser = TRUE;
            }
            else $errUser = TRUE;     
        }      
    }
    else
    {
        if(isset($_COOKIE[session_name()]))
        {
            header("Location: home.php");
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
                <form name="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <h1>ImagiNest</h1>
                    <input id="user" type="text" class="user" name="user" placeholder="Username/Email" required/>
                    <input id="pwd" type="password" class="pass" name="pwd" placeholder="Password" required/>
                    <?php 
                        if(isset($_COOKIE["success"])) 
                        {
                            echo $_COOKIE["success"]; 
                            setcookie("success","",time()-3600);
                        }
                        elseif(isset($errUser) && $errUser==TRUE) echo "<p class='alert alert-danger'>Could not login with these credentials!</p>";
                        elseif(isset($_COOKIE["updError"]))
                        {
                            
                            setcookie("updError","",time()-3600);
                        }
                        elseif(isset($_COOKIE["successPwd"])) 
                        {
                            echo $_COOKIE["successPwd"]; 
                            setcookie("successPwd","",time()-3600);
                        }
                    ?>
                    <input class="login" type="submit" value="Sign in"/>
                </form>
                    <div class="register">
                        <p>Don’t have an account yet?</p>
                        <a href="register.php">Sign Up</a>
                    </div>
                    <div class="register">
                    <a data-toggle="modal" data-target="#exampleModal">
                    Forgot password?
                    </a>
                    </div>
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Forgot Password?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="resetPasswordSend.php" method="POST">
                                        <input id="user" type="text" class="user" name="user" placeholder="Username/Email" required/>
                                        <input class="login" type="submit" value="Send Reset Password Email"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>