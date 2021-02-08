<?php

if(isset($_POST["login_button"])){

    require_once 'connect-to-database.php';
    require_once 'functions.php';

    $userLoginEmail= mysqli_real_escape_string($conn,$_POST["user_login_email"]);
    $userLoginPassword= mysqli_real_escape_string($conn,$_POST["user_login_password"]);

    if(emptyInputLogin($userLoginEmail, $userLoginPassword) !== false){
        header("Location: ../public/Pages/login.php?error=emptyinput");
        exit();
    }
    loginUser($conn, $userLoginEmail, $userLoginPassword);
}else{
    header("Location: ../public/Pages/login.php");
    exit();
}