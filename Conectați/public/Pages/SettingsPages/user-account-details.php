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
        $accDetails= "SELECT * FROM signup_database WHERE user_Id=".$_SESSION['userid']."";
        $accDetailsQuery=mysqli_query($conn,$accDetails);
        $accDetailsQueryRow=mysqli_fetch_assoc($accDetailsQuery);

        if(isset($_POST['change-details-submit'])){
            $changeUserName=mysqli_real_escape_string($conn,$_POST['change-user-name']);
            $changeEmail=mysqli_real_escape_string($conn,$_POST['change-email']);
            $changeContactNum=mysqli_real_escape_string($conn,$_POST['change-contactnum']);     
            $a=date('Y-m-d',strtotime($_POST['change-dateofbirth'])); 
            $changeAddress=mysqli_real_escape_string($conn,$_POST['change-address']);
            $changeAge=mysqli_real_escape_string($conn,$_POST['change-age']);
            $changeGender=mysqli_real_escape_string($conn,$_POST['change-gender']);
        
            
            $updateUserDetails = "UPDATE `signup_database` 
                SET `user_full_name` = '$changeUserName', 
                `user_email` = '$changeEmail', 
                `user_contactnum` = '$changeContactNum', 
                `user_dateofbirth` = '$a', 
                `user_address` = '$changeAddress', 
                `user_age` = '$changeAge', 
                `user_gender` = '$changeGender'
            WHERE `signup_database`.`user_Id` = ".$_SESSION['userid'].";";
        
            $stmtchange = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmtchange, $updateUserDetails)){
                header("../SettingsPages/user-account-details.php?changeError=stmterror");
                exit();
            }
            
            mysqli_stmt_bind_param($stmtchange, "sssssss", $changeUserName, $changeEmail, $changeContactnum, $a, $changeAddress, $changeAge,$changeGender);
            mysqli_stmt_execute($stmtchange);
            mysqli_stmt_close($stmtchange);
            header("Location: ../SettingsPages/user-account-details.php?changeError=changesuccessful");
            
        }

    ?>
    <div class="user-account-details-container">
        <div class="user-account-details-inner-container">
            <form method="POST">
                <p>Name</p>
                <input type="text" name="change-user-name" placeholder="<?php echo $accDetailsQueryRow['user_full_name']; ?> " required>
                
                <p>Email</p>
                <input type="text" name="change-email" placeholder="<?php echo $accDetailsQueryRow['user_email']; ?> " required>
                
                <p>Contact Number</p>
                <input type="text" name="change-contactnum" placeholder="<?php echo $accDetailsQueryRow['user_contactnum']; ?> " required>
                
                <p>Date of Birth</p>
                <input type="date" name="change-dateofbirth" placeholder="<?php echo $accDetailsQueryRow['user_dateofbirth']; ?> " required>
                
                <p>Address</p>
                <input type="text" name="change-address" placeholder="<?php echo $accDetailsQueryRow['user_address']; ?> " required>
                
                <p>Age</p>
                <input type="text" name="change-age" placeholder="<?php echo $accDetailsQueryRow['user_age']; ?> " required>
                
                <p>Gender</p>
                <input type="text" name="change-gender" placeholder="<?php echo $accDetailsQueryRow['user_gender']; ?> " required>
                
                <button type="submit" name="change-details-submit">Save</button>
            </form>
            <?php
                if(isset($_GET['changeError'])){
                    if($_GET['changeError']=='changesuccessful'){
                        echo "<p style='position:relative; top:60px; left:10px; color:green;'> ACCOUNT DETAILS CHANGED </p>";
                    }
                }
            ?>
        </div>
    </div>
</body>
</html>