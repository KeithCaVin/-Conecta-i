<?php
    include '../../../private/connect-to-database.php';
    include '../../../private/functions.php';
    session_start();

    $sql= "SELECT * FROM `signup_database` WHERE `signup_database`.`user_Id`=". $_SESSION["userid"]."";
    $result=mysqli_query($conn,$sql);
    $resultCheck= mysqli_num_rows($result);
    $row=mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conectați | Home</title>
    <link rel="stylesheet" href="../../SCSS/Main.css">
</head>
<body>
    <!-------------Header and side pannel start------------------>
    <div class="home-page-header-container">
        <header><p>Conectați</p></header>
        <?php
        if(isset($_SESSION['userid'])){
            echo "<a href='../../../private/log-out.php'><img src='../../Images/logout.png' width='20px'; height='20px';></a>";
        }
    ?>
    </div>
    
    <!--- BORDERS--->
    <div class="border-right"></div>
    <div class="border-between"></div>

    <div class="navigate-to-pages-container">
        <div class="navigate-to-pages-inner-container">

            <!--User Profile-->
            <div class="user-profile-container">
                <div class="user-profile-inner-container">
                    <div class="user-profile-img">
                        <img src="../../../private/ProfilePics/<?=$row['user_profile_picture']?>" width="50px" height="50px"/>
                    </div>
                    <div class="user-profile-name">
                        <?php
                            echo "<p>" . $row['user_full_name'] . "</p>";
                        ?>
                    </div>
                </div>
            </div>

            <!--Navigate to pages-->
            <div class="user-navigation-container">
                <div class="user-navigation-inner-container">
                    <a class="item1" href="../user-feeds.php">Home</a>
                    <a class="item2" href="../user-connections.php">Connections</a>
                    <a class="item3" href="../user-profile.php">Profile</a>
                    <a class="item4" href="../user-settings.php">Settings</a>
                </div>
            </div>
        </div>
    </div>
    <!-------------Header and side pannel End------------------>

    <?php
        if(isset($_POST['submit-new-password'])){
            $oldPassword=mysqli_real_escape_string($conn,$_POST['old-password']);
            $newPassword=mysqli_real_escape_string($conn,$_POST['new-password']);
            $confirmNewPassword=mysqli_real_escape_string($conn,$_POST['confirm-new-password']);

            $changePassword= "UPDATE signup_database SET user_password= '".$newPassword."' WHERE user_Id='".$_SESSION['userid']."'";
            $checkIfPasswordMatch = "SELECT user_password FROM signup_database WHERE user_Id=".$_SESSION['userid']."";
            $checkIfPasswordMatchQuery= mysqli_query($conn,$checkIfPasswordMatch);
            $checkIfPasswordMatchRow=mysqli_fetch_array($checkIfPasswordMatchQuery);

            $getOldPassword= $checkIfPasswordMatchRow['user_password'];
            if($getOldPassword != $oldPassword){
               
                header("Location: ../SettingsPages/user-change-password.php?changePasswordError=oldPasswordIsIncorrect");
            }else{
                if($newPassword!=$confirmNewPassword){
                    header("Location: ../SettingsPages/user-change-password.php?changePasswordError=passwordDontMatch");
                }else{
                    mysqli_query($conn,$changePassword);
                    header("Location: ../SettingsPages/user-change-password.php?changePasswordError=changePasswordSuccess");
                } 
            }
             
        }
    
    ?>
    <div class="change-password-container">
        <div class="change-password-inner-container">
            <form method="POST">
                <input class="oldP" type="password" name="old-password" placeholder="Old Password" required>
                <input class="newP" type="password" name="new-password" placeholder="New Password" required>
                <input class="confirmP" type="password" name="confirm-new-password" placeholder="Confirm Password" required>
                <button type="submit" name="submit-new-password">Confirm</button>
            </form>
            <?php
                if(isset($_GET['changePasswordError'])){
                    if($_GET['changePasswordError'] == 'oldPasswordIsIncorrect'){
                        echo "<p style='margin-left:15px; margin-top:10px; color:red;'>Old Password is Incorrect</p>";
                    }
                    if($_GET['changePasswordError'] == 'passwordDontMatch'){
                        echo "<p style='margin-left:20px; margin-top:10px; color:red;'>Password doesn't match</p>";
                    }
                    if($_GET['changePasswordError'] == 'changePasswordSuccess'){
                        echo "<p style='margin-left:40px; margin-top:10px; color:lightgreen;'>Password changed!</p>";
                    }

                }
            ?>
        </div>
    </div>
</body>
</html>