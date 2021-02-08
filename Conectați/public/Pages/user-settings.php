<?php
    include '../../private/connect-to-database.php';
    include '../../private/functions.php';
    session_start();

    if(isset($_POST["upload_user_profile_pic_btn"])){

        $filename = $_FILES["userprofilepicupload"]["name"];
        $tempname = $_FILES["userprofilepicupload"]["tmp_name"];
        $folder = "../../private/ProfilePics/".$filename;
        
        $profilePicUploadQuery= "UPDATE `signup_database` SET `user_profile_picture` = '$filename' WHERE `signup_database`.`user_Id`=". $_SESSION["userid"]."";
        
        mysqli_query($conn, $profilePicUploadQuery);
        
        if (move_uploaded_file($tempname, $folder))  { 
            $msg = "Image uploaded successfully"; 
        }else{ 
            $msg = "Failed to upload image"; 
        } 
    }
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
    <link rel="stylesheet" href="../SCSS/Main.css">
</head>
<body>
    <!-------------Header and side pannel start------------------>
    <div class="home-page-header-container">
        <header><p>Conectați</p></header>
        <?php
        if(isset($_SESSION['userid'])){
            echo "<a href='../../private/log-out.php'><img src='../Images/logout.png' width='20px'; height='20px';></a>";
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
                        <img src="../../private/ProfilePics/<?=$row['user_profile_picture']?>" width="50px" height="50px"/>
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
                    <a class="item1" href="user-feeds.php">Home</a>
                    <a class="item2" href="user-connections.php">Connections</a>
                    <a class="item3" href="user-profile.php">Profile</a>
                    <a class="item4" href="user-settings.php">Settings</a>
                </div>
            </div>
        </div>
    </div>
    <!-------------Header and side pannel End------------------>



    <div class="settings-nav-to-pages">
        <form method="POST" enctype='multipart/form-data'>
       
            <img id="output" height="300px" width="300px" src="../../private/ProfilePics/<?=$row['user_profile_picture']?>" width="50px" height="50px"/>
            <input type="file" name="userprofilepicupload" id="file" style="display: none;" onchange="loadFileProfPic(event)">
            <p><label for="file" style="cursor: pointer;">Change profile picture</label></p>
        
            <button type="submit" name="upload_user_profile_pic_btn">Save</button>
            
        </form>

        <a href="SettingsPages/user-account-details.php" class="a1">Edit account details</a>
        <a href="SettingsPages/user-change-password.php" class="a2">Change Password</a>
    </div>

    <script>
        var loadFileProfPic = function(event) {
	    var image = document.getElementById('output');
	    image.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
</body>
</html>
