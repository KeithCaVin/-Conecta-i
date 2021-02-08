<?php
    include '../../private/connect-to-database.php';
    include '../../private/functions.php';
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
    <link rel="stylesheet" href="../SCSS/Main.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
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

    <?php
        $userIsFollowed=false;
        
        if (isset($_GET["othersUserId"])) {
            $searchQuery= "SELECT * FROM `signup_database` WHERE `user_Id`=".$_GET['othersUserId']."";
            $searchResult= mysqli_query ($conn, $searchQuery);
            $searchResultRow= mysqli_fetch_assoc($searchResult);

            

            if(isset($_POST['follow_user'])){
                $userIsFollowed=true;
                $followUser="INSERT INTO `tbl_follow`(sender_Id,receiver_Id) VALUES ('".$_SESSION['userid']."', '".$searchResultRow['user_Id']."')";
                mysqli_query($conn,$followUser);


            }
            if(isset($_POST['unfollow_user'])){
                $userIsFollowed=false;
                $unFollowUser="DELETE FROM `tbl_follow` WHERE `sender_Id`=".$_SESSION['userid']." AND `receiver_Id`=".$searchResultRow['user_Id']."";
                mysqli_query($conn,$unFollowUser);
             

            }
            if ($_GET["othersUserId"]== $searchResultRow['user_Id'] ) {
                $checkIfFollow= "SELECT * FROM `tbl_follow` WHERE `sender_Id`='".$_SESSION['userid']."' AND receiver_Id='".$searchResultRow['user_Id']."'";
                $checkIfFollowResult= mysqli_query($conn, $checkIfFollow);
                $checkIfFollowResultRow=mysqli_num_rows($checkIfFollowResult);
                echo "
                    <div class='user-page-profile-container'>
                        <div class='user-page-profile-inner-container'>

                            <div class='user-page-profile-header'>
                            <div class='user-page-profile-header-pfp'>
                                <img src='../../private/ProfilePics/".$searchResultRow['user_profile_picture']."' width='70px' height='70px'/>
                            </div>
                            <div class='user-page-profile-header-name'>
                                <p>".$searchResultRow['user_full_name']."</p>
                            </div>
                            
                            "; 
                           
                            if ($checkIfFollowResultRow>0) {
                                echo "<form method='POST'><button type='submit' name='unfollow_user' style='
                                background-color:#6090A8;
                                border-radius:5px;
                                display:inline-block;
                                border-style: none;
                                cursor:pointer;
                                color:white;
                                width: 60px;
                                height: 20px;
                                margin-left: 370px;' >Unfollow</button></form>";
                                
                            } else {
                                
                                echo "<form method='POST'><button type='submit' name='follow_user' style='
                                background-color:#6090A8;
                                border-radius:5px;
                                display:inline-block;
                                border-style: none;
                                cursor:pointer;
                                color:white;
                                width: 60px;
                                height: 20px;
                                margin-left: 370px;'>Follow</button></form>";
                                
                            }
                         echo "
                        </div>
                        ";
                        
                        

                        $sqlPost= 'SELECT * FROM `user_upload` WHERE `user_upload`.`user_Id`='. $searchResultRow['user_Id'].'';
                        $showPostQuery=mysqli_query($conn, $sqlPost);
                        $showPostQueryCheck= mysqli_num_rows($result);
            
                        if($showPostQueryCheck >0){
                            while($showPostRow=mysqli_fetch_assoc($showPostQuery)){
                                echo 
                                    "
                                    <div class='user-profile-posts'>
                                        <div class='user-profile-post-pfp'>
                                            <img src='../../private/ProfilePics/".$searchResultRow['user_profile_picture']."' width='50px' height='50px'/>
                                        </div>
                                        <div class='user-profile-post-name'>
                                    
                                        ".$searchResultRow['user_full_name']. "
                                             
                                        </div>
                                    

                                        <div class='user-profile-post-contents'>

                                            <div class='user-profile-post-content-img'>
                                                <img src='../../private/UploadedImg/".$showPostRow['upload_img']."' width='90%' height='100%'/>
                                            </div>
                                            <div class='user-profile-post-content-text'>
                                            <p>".
                                            $showPostRow['upload_text']
                                            ."</p>
                                            </div>

                                        </div>
                                    </div>
                                ";
                            }
                        }
            echo"</div>
        
        </div>
                ";
            }
        }
    ?>    
    
</body>
</html>

