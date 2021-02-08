<?php
    include '../../private/connect-to-database.php';
    include '../../private/functions.php';
    session_start();
    
    $sql= "SELECT * FROM `signup_database` WHERE `signup_database`.`user_Id`=". $_SESSION["userid"]."";
    $result=mysqli_query($conn,$sql);
    $resultCheck= mysqli_num_rows($result);
    $row=mysqli_fetch_assoc($result);

    ///Display posts
    if(isset($_GET['delete_post'])){
        $uploadId = $_GET['delete_post'];
        $postDelete="DELETE FROM `user_upload` WHERE upload_id=".$uploadId."";
        mysqli_query($conn,$postDelete);
        
    }

    ///--------------------UPLOAD COMMENT FUNCTION--------------------///
if(isset($_POST['submit-comment'])){
    $sql= "SELECT * FROM `signup_database` WHERE `signup_database`.`user_Id`=". $_SESSION["userid"]."";
    $result=mysqli_query($conn,$sql);
    $resultCheck= mysqli_num_rows($result);
    $row=mysqli_fetch_assoc($result);

    

    $uploadComment= mysqli_real_escape_string($conn,$_POST['user-comment']);
    $parentUploadId= $_POST['parent_upload_id'];

    $postCommentQuery= "INSERT INTO `post_comments` (`parent_upload_Id`,`comment_text`, `comment_upload_time`, `user_Id`, `user_email`) 
    VALUES ('$parentUploadId','$uploadComment' ,current_timestamp(), '".$_SESSION['userid']."', '".$row['user_email']."');";

    mysqli_query($conn,$postCommentQuery);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conectați | Home</title>
    <link rel="stylesheet" href="../SCSS/Main.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    
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


    <div class="user-page-profile-container">
        <div class="user-page-profile-inner-container">

            <div class="user-page-profile-header">
                <div class="user-page-profile-header-pfp">
                    <img src="../../private/ProfilePics/<?=$row['user_profile_picture']?>" width="70px" height="70px"/>
                </div>
                <div class="user-page-profile-header-name">
                    <?php
                        echo "<p>" . $row['user_full_name'] . "</p>";
                    ?>
                </div>
            </div>
      <!--------------THIS FUNCTION WILL DISPLAY THE USER'S PROFILE ----------->
            <?php
                $sqlPost= "SELECT * FROM `user_upload` WHERE `user_upload`.`user_Id`=". $_SESSION["userid"]." ORDER BY `user_upload`.`upload_time` DESC";
                $showPostQuery=mysqli_query($conn, $sqlPost);
                $showPostQueryCheck= mysqli_num_rows($result);
        
                if($showPostQueryCheck >0){
                    while($showPostRow=mysqli_fetch_assoc($showPostQuery)){
                        $selectPostId="SELECT * FROM `rating_info` WHERE user_Id='".$_SESSION['userid']."' AND post_Id='".$showPostRow['upload_Id']."'";
                        $selectPostIdQuery=mysqli_query($conn, $selectPostId);
                        $selectPostIdQueryRow=mysqli_num_rows($selectPostIdQuery);
                        echo 
                            "
                            <div class='user-profile-posts'>
                                <div class='user-profile-posts-header'>
                                    <div class='user-profile-post-pfp'>
                                        <img src='../../private/ProfilePics/".$row['user_profile_picture']."' width='50px' height='50px'/>
                                    </div>
                                    <div class='user-profile-post-name'>
                                    
                                    ".$row['user_full_name'] . "
                                    
                                    </div>
                                    <div class='user-profile-post-delete-or-edit'>
                                        <button><a href='user-profile.php?delete_post=".$showPostRow['upload_Id']."' name= 'delete_post'>
                                        <img src='../Images/delete.png'  width='15px' height='15px'>
                                        </a></button>
                                        
                                        <button><a href='edit-post.php?post=".$showPostRow['upload_Id']."' name='edit_post'>
                                        <img src='../Images/edit.png' width='15px' height='15px'>
                                        </a></button>
                                    </div>
                                </div>

                                <div class='user-profile-post-contents'>

                                    <div class='user-profile-post-content-img'>
                                        <img src='../../private/UploadedImg/".$showPostRow['upload_img']."' width='90%' height='100%'/>
                                    </div>
                                    <div class='user-profile-post-content-text'>
                                    <p>".
                                    $showPostRow['upload_text']
                                    ."</p>
                                    <p class='show_time'>-".$showPostRow['upload_time']."</p>
                                    </div>
                                    <div class='line1'></div>
                                    <div class='line2'></div>

                                    <!---Likes and comments section-->
                                    <div class='user-profile-post-likes-and-comments'>
                                    "; if($selectPostIdQueryRow>0){
                                            echo " <i class='fa fa-thumbs-up like-btn'";
                                        }else{
                                            echo " <i class='fa fa-thumbs-o-up like-btn'";
                                        }
                                    echo"
                                    data-id='".$showPostRow['upload_Id']."'></i>
                                        <p class='comments'>Comments</p>
                                    </div>
                                    
                                    <!---upload a comment by the user--->
                                    <div class='user-profile-post-upload-comments'>
                                        <div class='user-profile-post-upload-comments-pfp'>
                                            <img src='../../private/ProfilePics/".$row['user_profile_picture']."' width='30px' height='30px'/>
                                        </div>
                                        <div class='user-profile-post-upload-comments-name'>
                                            <p>". $row['user_full_name'] . "</p>
                                        </div>
                
                                        <form method='POST'>
                                            <div class='user-profile-post-upload-comments-input'>
                                                <textarea name='user-comment' placeholder='Write a comment...' rows='4' cols='80' ></textarea>
                                            </div>
                                            <div class='user-profile-post-upload-comments-submit'>
                                                <input type='hidden' name='parent_upload_id' value='".$showPostRow['upload_Id']."' />
                                                <button type='submit' name='submit-comment' class='submit-comment-btn'>Upload</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!--- Friend's comment section--->";
                                    ///------------------------display comments------------------------///
                                    $comments="SELECT * FROM post_comments WHERE parent_upload_Id='".$showPostRow['upload_Id']."'";
                                    $commentsQuery= mysqli_query($conn, $comments);
                                    $commentsQueryCheck= mysqli_num_rows($commentsQuery);
                                   ///-----------------DELETE COMMENTS--------------///
                                   if(isset($_GET['deleteComment'])){
                                        $deleteCommentId= $_GET['deleteComment'];
                                        $deleteComment="DELETE FROM post_comments WHERE comment_Id='".$deleteCommentId."'";
                                        mysqli_query($conn, $deleteComment);
                                        echo "<meta http-equiv='refresh' content='0;url=user-profile.php'>";
                                    }
                                    if($commentsQueryCheck>0){
                                        while ($commentsQueryCheckRow=mysqli_fetch_assoc($commentsQuery)) {
                                            $commentDetails= "SELECT signup_database.user_full_name, signup_database.user_profile_picture FROM signup_database INNER JOIN post_comments ON
                                            signup_database.user_Id = post_comments.user_Id WHERE post_comments.user_Id='".$commentsQueryCheckRow['user_Id']."'";
                                            $commentDetailsQuery=mysqli_query($conn,$commentDetails);
                                            $commentDetailsQueryRow=mysqli_fetch_assoc($commentDetailsQuery);

                                            
                                            echo"
                                            <div class='user-profile-post-friend-comments'>
                                                <div class='user-profile-post-friend-comments-pfp'>
                                                    <img src='../../private/ProfilePics/".$commentDetailsQueryRow['user_profile_picture']."' width='30px' height='30px'/>
                                                </div>
                                                <div class='user-profile-post-friend-comment-name'>
                                                    <p>". $commentDetailsQueryRow['user_full_name'] . "</p>
                                                </div>
                                                <div class='user-profile-delete-comment'>
                                                    <button><a href='user-profile.php?deleteComment=".$commentsQueryCheckRow['comment_Id']."'>
                                                    <img src='../Images/delete.png'  width='15px' height='15px'>
                                                    </a></button>
                                                </div>
                                                <div class='user-profile-post-friend-comment-content'>
                                                    <p>".$commentsQueryCheckRow['comment_text']."</p>
                                                    <i style='font-size:12px;'>-".$commentsQueryCheckRow['comment_upload_time']."</i>
                                                </div>
                                            </div>
                                            ";
                                        }
                                    }
                                    echo"
                                </div>
                            </div>
                        ";
                    }
                }
            ?>

        </div>
       
    </div>
    <script src="../script.js"></script>
</body>
</html>

