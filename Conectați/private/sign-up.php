<?php

if(isset($_POST["signup_submit"])){

    require_once "connect-to-database.php";
    require_once "functions.php";

    $userFullName = mysqli_real_escape_string($conn,$_POST["user_full_name"]);
    $userEmail = mysqli_real_escape_string($conn,$_POST["user_email"]);
    $userPassword = mysqli_real_escape_string($conn,$_POST["user_password"]);
    $userPasswordRepeat = mysqli_real_escape_string($conn,$_POST["user_password_repeat"]);
    $userContactnum = mysqli_real_escape_string($conn,$_POST["user_contact_number"]);
    $userDoB = $_POST["user_date_of_birth"];
    $userAddress = mysqli_real_escape_string($conn,$_POST["user_address"]);
    $userAge = mysqli_real_escape_string($conn,$_POST["user_age"]);
    $userGender = mysqli_real_escape_string($conn,$_POST["user_gender"]);
    
    if(emptyInput($userFullName,$userEmail,$userPassword,$userPasswordRepeat,$userContactnum,$userDoB,$userAddress,$userAge,$userGender) !== false){
        header("Location: ../public/index.php?error=EmptyInput");
        exit();
    }
    if(invalidEmail($userEmail) !== false){
        header("Location: ../public/index.php?error=InvalidEmail");
        exit();
    }
    if(passwordMatch($userPassword,$userPasswordRepeat) !== false){
        header("Location: ../public/index.php?error=PasswordDontMatch");
        exit();
    }
    if(emailExists($conn, $userEmail)){
        header("Location: ../public/index.php?error=EmailAlreadyExists");
        exit();
    }
    createUser($conn, $userFullName, $userEmail, $userPassword, $userContactnum, $userDoB, $userAddress, $userAge,$userGender);

}
else{
    header("Location: ../public/index.php");
}

?>