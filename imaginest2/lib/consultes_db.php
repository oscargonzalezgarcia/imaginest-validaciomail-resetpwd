<?php

    function getCountUser($user)
    {
        require('connecta_db.php');
        try
        {
            $sql = "SELECT count(username) FROM users WHERE username = '$user' OR email = '$user'";
            $num = $db->query($sql);
            $resultat = $num->fetch();
        }
        catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return $resultat[0];
    }

    function insertUser($userPOST,$emailPOST,$fNamePOST,$lNamePOST,$passPOST,$activationCode)
    {
        require('connecta_db.php');

        try
        {
            $sql = "INSERT INTO users(email, username, passHash,userFirstName, userLastName, activationCode,active) values ('$emailPOST','$userPOST','$passPOST','$fNamePOST','$lNamePOST','$activationCode',0)";
            $registre = $db->query($sql);
        }
        catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return $registre;
    }

    function getUserName($user)
    {
        require('connecta_db.php');

        try
        {
            $sql = "SELECT username FROM users WHERE email = '$user' OR username = '$user'";
            $userName = $db->query($sql);
            $resultat = $userName->fetch();
        }
        catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return $resultat[0];
    }

    function getExpiryTime($mail)
    {
        require('connecta_db.php');

        try
        {
            $sql = "SELECT count(username) FROM users WHERE email = '$mail' AND resetPassExpiry > current_timestamp";
            $num = $db->query($sql);
            $resultat = $num->fetch();
        }
        catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return $resultat[0];
    }

    function getResetCode($mail)
    {
        require('connecta_db.php');

        try
        {
            $sql = "SELECT resetPassCode FROM users WHERE email = '$mail'";
            $code = $db->query($sql);
            $resultat = $code->fetch();
        }
        catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return $resultat[0];
    }

    function getEmail($user)
    {
        require('connecta_db.php');

        try
        {
            $sql = "SELECT email FROM users WHERE email = '$user' OR username = '$user'";
            $email = $db->query($sql);
            $resultat = $email->fetch();
        }
        catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return $resultat[0];
    }

    function getCountActiveUser($user)
    {
        require('connecta_db.php');

        try
        {
            $sql = "SELECT count(username) FROM users WHERE (email = '$user' OR username = '$user') AND active = 1";
            $num = $db->query($sql);
            $resultat = $num->fetch();
        }
        catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return $resultat[0];
    }
    
    function isPasswordCorrect($user,$pass)
    {
        require('connecta_db.php');

        try
        {
            $sql = "SELECT passHash FROM users WHERE email = '$user' OR username = '$user'";
            $resultat = $db->query($sql);
            $passHash = $resultat->fetch();
        }
        catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return password_verify($pass,$passHash[0]);
    }

    function updateUser($user)
    {
        require('connecta_db.php');

        try
        {
            $sql = "UPDATE users SET lastSignIn = current_timestamp WHERE email = '$user' OR username = '$user'";
            $resultat = $db->query($sql);
        }
        catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }

    function updateUserPass($resetPassCode,$userPOST)
    {
        require('connecta_db.php');

        try
        {
            $sql = "UPDATE users SET resetPassCode = '$resetPassCode', resetPass = 1, resetPassExpiry = DATE_ADD(current_timestamp, INTERVAL 30 MINUTE) WHERE email = '$userPOST' OR username = '$userPOST'";
            $resultat = $db->query($sql);
        }
        catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }

    function updatePass($pass,$mail)
    {
        require('connecta_db.php');

        try
        {
            $sql = "UPDATE users SET passHash = '$pass' WHERE email = '$mail'";
            $resultat = $db->query($sql);
        }
        catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }

    function nullResetPassword($mail)
    {
        require('connecta_db.php');

        try
        {
            $sql = "UPDATE users SET resetPassCode = null, resetPass = null, resetPassExpiry = null WHERE email = '$mail'";
            $resultat = $db->query($sql);
        }
        catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }

    function getActivationCode($email)
    {
        require('connecta_db.php');

        try
        {
            $sql = "SELECT activationCode from users where email = '$email'";
            $code = $db->query($sql);
            $resultat = $code->fetch();
        }
        catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }

        return $resultat[0];
    }

    function activateUser($email)
    {
        require('connecta_db.php');

        try
        {
            $sql = "UPDATE users SET active = 1, activationDate = current_timestamp, activationCode = NULL WHERE email = '$email'";
            $resultat = $db->query($sql);
        }
        catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
            setcookie("updError","<p class='alert alert-danger'>We could not activate your account!</p>",time()+3600);
        }
    }