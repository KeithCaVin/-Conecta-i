<?php
require 'connect-to-database.php';
function emptyInput($userFullName,$userEmail,$userPassword,$userPasswordRepeat,$userContactnum,$userDoB,$userAddress,$userAge,$userGender){

    if(empty($userFullName)||empty($userEmail)|| empty($userPassword)||empty($userPasswordRepeat)||empty($userContactnum)||
       empty($userDoB)||empty($userAddress)||empty($userAge)||empty($userGender)){
        $result = true;
    }else{
        $result = false;
    }
    return $result;
}

function invalidEmail($userEmail)
{

    if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL))
    {
        $result= true;
    }
    else
    {
        $result=false;
    }
    return $result;
}
function passwordMatch($userPassword,$userPasswordRepeat){

    if($userPassword !== $userPasswordRepeat){
        $result= true;
    }else{
        $result=false;
    }
    return $result;
}

function emailExists($conn, $userEmail){
  
    $sql= "SELECT * FROM signup_database WHERE user_email = ?;";
    $stmt = mysqli_stmt_init($conn);
    
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../public/index.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);

    $resultData= mysqli_stmt_get_result($stmt);

    if($row=mysqli_fetch_assoc($resultData)){
        return $row;
    }else{
        $result = false;
    }
    return $result;

    mysqli_stmt_close($stmt);
}


function createUser($conn, $userFullName, $userEmail, $userPassword, $userContactnum, $userDoB, $userAddress, $userAge,$userGender){

    $sql= "INSERT INTO signup_database (user_full_name, user_email, user_password, user_contactnum, user_dateofbirth, user_address, user_age, user_gender) 
    VALUES ('$userFullName','$userEmail','$userPassword','$userContactnum','$userDoB','$userAddress','$userAge','$userGender');";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../public/index.php?error=stmtfailed");
        exit();
    }
    $hashedPwd = password_hash($userPassword, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ssssssss", $userFullName, $userEmail, $hashedPwd, $userContactnum, $userDoB, $userAddress, $userAge,$userGender);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: ../public/index.php?error=none");
   exit();
}

function emptyInputLogin($userLoginEmail, $userLoginPassword){

    if(empty($userLoginEmail)||empty($userLoginPassword)){
        $result = true;
    }else{
        $result = false;
    }
    return $result;
}
function loginUser($conn, $userLoginEmail, $userLoginPassword){
    $emailExists=  emailExists($conn, $userLoginEmail);

    if($emailExists === false){
        header("Location: ../public/Pages/login.php?error=unkownaccount");
        exit();
    }
    
    if($userLoginPassword !==  $emailExists["user_password"]){
        header("Location: ../public/Pages/login.php?error=wrongpassword");
        exit();
    }else if ($userLoginPassword ===  $emailExists["user_password"]){
        session_start();
        $_SESSION["userid"] = $emailExists["user_Id"];
        $_SESSION["userFullName"]=$emailExists["user_full_name"];
        $_SESSION["userEmail"]=$emailExists["user_email"];
        $_SESSION["userPassword"]=$emailExists["user_password"];
        $_SESSION["userContactNum"]=$emailExists["user_contactnum"];
        $_SESSION["userDateOfBirth"]=$emailExists["user_dateofbirth"];
        $_SESSION["userAddress"]=$emailExists["user_address"];
        $_SESSION["userAge"]=$emailExists["user_age"];
        $_SESSION["userGender"]=$emailExists["user_gender"];
        $_SESSION["userProfilePicture"]=$emailExists["user_profile_picture"];
        
        header("Location: ../public/Pages/user-feeds.php");
        exit();
    }
}

