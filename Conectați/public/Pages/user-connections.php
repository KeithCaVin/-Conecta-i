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


    <!-------------Page Content Container------------------>
    <div class="user-connection-container">
        <div class="user-connection-list">
            <h1>Connections</h1>
            
            <?php 
                $query= "SELECT signup_database.user_full_name, signup_database.user_profile_picture, signup_database.user_Id
                 FROM signup_database INNER JOIN tbl_follow ON signup_database.user_Id = tbl_follow.receiver_Id 
                WHERE sender_Id =".$_SESSION['userid']."
                ";
                $queryResult=mysqli_query($conn,$query);
                $queryResultCheck= mysqli_num_rows($queryResult);

                if ($queryResultCheck >0) {
                    while($followRow=mysqli_fetch_assoc($queryResult)){
                        echo "
                            <div class='user-connection-list-container'>
                            <img src='../../private/ProfilePics/".$followRow['user_profile_picture']."' height='50px' width='50px'>
                            <p><a href='other-user-profile.php?othersUserId=".$followRow['user_Id']."'>".$followRow['user_full_name']."</a></p>
                            
                            </div>
                        ";
                    }
                }
            ?>
           
            
            
        </div>

        
        <div class="user-connection-search">
            <div class="user-connection-search-container">
                <form method="POST">
                    <input type="text" placeholder="Search" name="search-users">
                    <button type="submit" name="search-user-btn">Search</button>
                </form> 
            </div>
            <?php
                if(isset($_POST['search-user-btn'])){
                    $searchUserName=mysqli_real_escape_string($conn,$_POST['search-users']);
                    
                    $searchQuery= "SELECT * FROM `signup_database`  WHERE CONCAT(`user_full_name`) LIKE '%".$searchUserName."%'";
                    $searchResult= mysqli_query ($conn, $searchQuery);
                    $searchResultCheck= mysqli_num_rows ($searchResult);

                    if($searchResultCheck >0){
                        while($searchResultRow= mysqli_fetch_assoc($searchResult))
                        {
                            echo"
                            <div class='user-connection-searched-user'>
                                <img src='../../private/ProfilePics/".$searchResultRow['user_profile_picture']."' height='50px' width='50px'>
                                <p> <a href='other-user-profile.php?othersUserId=".$searchResultRow['user_Id']."' style='text-decoration: none;'>".$searchResultRow['user_full_name']."</a></p> 
                                
                            </div>";
                        }
                    }
                }
            ?>
            
        </div>
    </div>

</body>
</html>

