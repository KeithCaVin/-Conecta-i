<?php
    include '../../private/connect-to-database.php';
    include '../../private/functions.php';

    session_start();
///--------------------LIKE FUNCTION--------------------///
    if(isset($_POST['action'])){
        $post_Id= $_POST['post_Id'];
        $action= $_POST['action'];

        switch($action){
            case 'like':
                $sql= "INSERT INTO  rating_info (user_Id, post_Id, rating_action) VALUES ('".$_SESSION['userid']."','$post_Id','$action')
                ON DUPLICATE KEY UPDATE rating_action='like'
                "; 
                break;
            
            case 'unlike':
                $sql= "DELETE FROM rating_info WHERE user_Id='".$_SESSION['userid']."' AND post_Id='$post_Id'
                ";
                break;
            default:
                break;
        }
        mysqli_query($conn, $sql);
        exit(0);
    }
    
///--------------------UPLOAD POST FUNCTION--------------------///
    $sql= "SELECT * FROM `signup_database` WHERE `signup_database`.`user_Id`=". $_SESSION["userid"]."";
    $result=mysqli_query($conn,$sql);
    $resultCheck= mysqli_num_rows($result);
    $row=mysqli_fetch_assoc($result);
    
    if (isset($_POST["upload_post"])) {
        $uploadText=mysqli_real_escape_string($conn,$_POST["user-caption"]);
    
        $filename = $_FILES["user-image-post"]["name"];
        $tempname = $_FILES["user-image-post"]["tmp_name"];
        $folder = "../../private/UploadedImg/".$filename;
        
        $query="INSERT INTO `user_upload` (`upload_text`, `upload_img`, `upload_time`, `user_Id`) 
        VALUES ('$uploadText', '$filename', current_timestamp(),".$_SESSION['userid']. ");";
        if(empty($uploadText) && empty($filename)){
            header("Location: user-feeds.php?error=NothingToUpload");
            exit();
        }else{
            mysqli_query($conn,$query);

            echo "<meta http-equiv='refresh' content='0;url=user-feeds.php?error=UploadSuccess'>";
            if (move_uploaded_file($tempname, $folder))  { 
                $msg = "Image uploaded successfully"; 
            }else{ 
                $msg = "Failed to upload image"; 
            } 
            exit();
        }
        
    }
///--------------------DELETE POST FUNCTION--------------------/// 
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
    
    <!------Upload CONTENT CONTAINER ---->
    <div class="content-container">

        <!------Upload header ---->
        <div class="pre-upload-header">
            <div class="user-profile-img">

                <img src="../../private/ProfilePics/<?=$row['user_profile_picture']?>" width="50px" height="50px"/>
           
            </div>

            <div class="user-profile-name">
                <?php
                    echo "<p>" . $row['user_full_name'] . "</p>";
                ?>
            </div>
        </div>

        <!--- upload container--->
        <div class="user-input">
            <form method="POST" enctype='multipart/form-data'>
                <div class="image-upload-container">
                    <img id="output" />	
                    <input type="file" name="user-image-post" id="file" style="display: none;" onchange="loadFile(event)">
                    <p><label for="file" style="cursor: pointer;"><img src="../Images/gallery-icon.png" height="40px" width="40px"></label></p>
                </div>
            
                <div class="text-upload-container">
                    <textarea name="user-caption" rows="5" cols="80" placeholder="Share your story"></textarea>
                </div>
                <div class="button-upload-container">
                    <button type="submit" name="upload_post" class="upload-btn">Post</button>
                </div>
                
            </form>
            <?php
                    if (isset($_GET["error"])) {
                        if ($_GET["error"]== "NothingToUpload") {
                            echo "<p style='position: relative; top: -50px;  left:320px; color:red;'> Nothing to Upload! </p>";
                        }
                        if ($_GET["error"]== "UploadSuccess") {
                            echo "<p style='position: relative; top: -50px; left:320px; color:red;'> Post Uploaded! </p>";
                        }
                    }
                ?>
        </div>
    </div>
    
    
   <!---------------------------------- Retrieve OTHER'S USER'S post--------------------------------->
   <?php
       $sqlPost= "SELECT user_upload.upload_text, user_upload.upload_img, user_upload.upload_Id, signup_database.user_full_name, signup_database.user_profile_picture
       FROM signup_database INNER JOIN tbl_follow ON signup_database.user_Id = tbl_follow.receiver_Id INNER JOIN user_upload ON tbl_follow.receiver_Id = user_upload.user_Id
       WHERE sender_Id =".$_SESSION['userid']."
       ";
        
        $showPostQuery=mysqli_query($conn, $sqlPost);
        $showPostQueryCheck= mysqli_num_rows($showPostQuery);

        
        if($showPostQueryCheck >0){
            while($showPostRow=mysqli_fetch_assoc($showPostQuery)){
                $selectPostId="SELECT * FROM `rating_info` WHERE user_Id='".$_SESSION['userid']."' AND post_Id='".$showPostRow['upload_Id']."'";
                $selectPostIdQuery=mysqli_query($conn, $selectPostId);
                $selectPostIdQueryRow=mysqli_num_rows($selectPostIdQuery);
                echo 
                "
                    <div class='user-feeds-container'>
                        <div class='user-feeds-inner-container'>
                

                            <div class='user-feeds-posts'>
                                <div class='user-feeds-post-header-container'>
                                    <div class='user-feeds-post-pfp'>
                                        <img src='../../private/ProfilePics/".$showPostRow['user_profile_picture']."' width='50px' height='50px'/>
                                    </div>
                                    <div class='user-feeds-post-name'>
                                        ".
                                        $showPostRow['user_full_name'] . 
                                        "
                                        
                                    </div>
                                    <div class='user-feeds-post-delete-or-edit'>
                                        
                                    </div>
                                </div>
                                <div class='user-feeds-post-contents'>

                                    <div class='user-feeds-post-img'>
                                        <img id='uploadedImg' src='../../private/UploadedImg/".$showPostRow['upload_img']."' width='100%' height='100%'/>
                                    </div>
                                    <div class='user-feeds-post-content-text'>
                                        <p>".
                                        $showPostRow['upload_text']
                                        ."</p>
                                        
                                    </div>
                                    <div class='line1'></div>
                                    <div class='line2'></div>

                                    <!---Likes and comments section-->
                                    <div class='user-feeds-post-likes-and-comments'>
                                        "; if($selectPostIdQueryRow>0){
                                            echo " <i class='fa fa-thumbs-up like-btn'";
                                        }else{
                                            echo " <i class='fa fa-thumbs-o-up like-btn'";
                                        }
                                        echo"
                                        data-id='".$showPostRow['upload_Id']."'></i>
                                        <p class='comments'>Comments</p>
                                    </div>
                                    <!---upload a comment by the user and friends--->
                                    <div class='user-feeds-post-upload-comments'>
                                        <div class='user-feeds-post-upload-comments-pfp'>
                                            <img src='../../private/ProfilePics/".$row['user_profile_picture']."' width='30px' height='30px'/>
                                        </div>
                                        <div class='user-feeds-post-upload-comments-name'>
                                            
                                            <p>". $_SESSION['userFullName'] . "</p>
                                            
                                        </div>
                                        <form method='POST'>
                                            <div class='user-feeds-post-upload-comments-input'>
                                                <textarea name='user-comment' placeholder='Write a comment...' rows='4' cols='80' ></textarea>
                                            </div>
                                            <div class='user-feeds-post-upload-comments-submit'>
                                                <input type='hidden' name='parent_upload_id' value='".$showPostRow['upload_Id']."' />
                                                <button type='submit' name='submit-comment' class='submit-comment-btn'>Upload</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!--- comment section--->
                                    ";
                                    ///------------------------display comments------------------------///
                                    $comments="SELECT * FROM post_comments WHERE parent_upload_Id='".$showPostRow['upload_Id']."'";
                                    $commentsQuery= mysqli_query($conn, $comments);
                                    $commentsQueryCheck= mysqli_num_rows($commentsQuery);

                                    if($commentsQueryCheck>0){
                                        while($commentsQueryCheckRow=mysqli_fetch_assoc($commentsQuery)){
                                            $commentDetails= "SELECT signup_database.user_full_name, signup_database.user_profile_picture FROM signup_database INNER JOIN post_comments ON
                                            signup_database.user_Id = post_comments.user_Id WHERE post_comments.user_Id='".$commentsQueryCheckRow['user_Id']."'";
                                            $commentDetailsQuery=mysqli_query($conn,$commentDetails);
                                            $commentDetailsQueryRow=mysqli_fetch_assoc($commentDetailsQuery);
                                            ////-------------DELETE COMMENTS-------------/////
                                            if(isset($_GET['deleteCommentInFeeds'])){
                                                $deleteCommentIdInFeeds= $_GET['deleteCommentInFeeds'];
                                                $deleteCommentInFeed="DELETE FROM post_comments WHERE comment_Id='".$deleteCommentIdInFeeds."'";
                                                mysqli_query($conn, $deleteCommentInFeed);
                                                echo "<meta http-equiv='refresh' content='0;url=user-feeds.php'>";
                                            }
                                            echo "
                                                <div class='user-feeds-post-friend-comments'>
                                                    <div class='user-feeds-post-friend-comments-pfp'>
                                                        <img src='../../private/ProfilePics/".$commentDetailsQueryRow['user_profile_picture']."' width='30px' height='30px'/>
                                                    </div>
                                                    <div class='user-feeds-post-friend-comment-name'>
                                                        <p>". $commentDetailsQueryRow['user_full_name'] . "</p>
                                                    </div>
                                                    <div class='user-feeds-delete-comment'>";
                                                    if($commentsQueryCheckRow['user_Id'] == $_SESSION['userid']){
                                                        echo "<button><a href='user-feeds.php?deleteCommentInFeeds=".$commentsQueryCheckRow['comment_Id']."'>
                                                        <img src='../Images/delete.png'  width='15px' height='15px'>
                                                        </a></button>";
                                                    }else{
                                                        echo "<button style='border:none;'></button>";
                                                    }
                                                    
                                                    echo "
                                                    </div>
                                                    <div class='user-feeds-post-friend-comment-content'>
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
                        </div>
                    </div>
                ";
            }
        }
    
    ?>
<!---------------------------------- Retrieve User's post--------------------------------->
    <?php
       $sqlPost= "SELECT * FROM `user_upload` WHERE `user_upload`.`user_Id`=". $_SESSION["userid"]." ORDER BY `user_upload`.`upload_time` DESC";
        
        $showPostQuery=mysqli_query($conn, $sqlPost);
        $showPostQueryCheck= mysqli_num_rows($showPostQuery);
       
        
        if($showPostQueryCheck >0){
            
            while($showPostRow=mysqli_fetch_assoc($showPostQuery)){
                $selectPostId="SELECT * FROM `rating_info` WHERE user_Id='".$_SESSION['userid']."' AND post_Id='".$showPostRow['upload_Id']."'";
                $selectPostIdQuery=mysqli_query($conn, $selectPostId);
                $selectPostIdQueryRow=mysqli_num_rows($selectPostIdQuery);
        
                
                echo 
                "
                    <div class='user-feeds-container'>
                        <div class='user-feeds-inner-container'>
                
                            <div class='user-feeds-posts'>
                                <div class='user-feeds-post-header-container'>
                                    <div class='user-feeds-post-pfp'>
                                        <img src='../../private/ProfilePics/".$row['user_profile_picture']."' width='50px' height='50px'/>
                                    </div>
                                    <div class='user-feeds-post-name'>
                                        ".
                                        $row['user_full_name'] . 
                                        "
                                    </div>  
                                    <div class='user-feeds-post-delete-or-edit'>
                                        <button><a href='user-feeds.php?delete_post=".$showPostRow['upload_Id']."' name= 'delete_post'>
                                        <img src='../Images/delete.png'  width='15px' height='15px'>
                                        </a></button>
                                        <button><a href='edit-post.php?post=".$showPostRow['upload_Id']."' name='edit_post'>
                                        <img src='../Images/edit.png' width='15px' height='15px'>
                                        </a></button>
                                    </div>
                                </div>
                                
                                <div class='user-feeds-post-contents'>

                                    <div class='user-feeds-post-img'>
                                        <img id='uploadedImg'src='../../private/UploadedImg/".$showPostRow['upload_img']."' width='100%' height='100%' />
                                    </div>
                                    <div class='user-feeds-post-content-text'>
                                        <p>".
                                        $showPostRow['upload_text']
                                        ."</p>
                                        <p class='show_time'>-".$showPostRow['upload_time']."</p>
                                    </div>
                                    <div class='line1'></div>
                                    <div class='line2'></div>

                                    <!---Likes and comments section-->
                                    <div class='user-feeds-post-likes-and-comments'>
                                        "; if($selectPostIdQueryRow>0){
                                                echo " <i class='fa fa-thumbs-up like-btn'";
                                            }else{
                                                echo " <i class='fa fa-thumbs-o-up like-btn'";
                                            }
                                            echo"
                                            data-id='".$showPostRow['upload_Id']."'></i>
                                        <span class='likes'></span>
                                        <p class='comments'>Comments</p>
                                    </div>
                                    <!---upload a comment by the user and friends--->
                                    <div class='user-feeds-post-upload-comments'>
                                        <div class='user-feeds-post-upload-comments-pfp'>
                                            <img src='../../private/ProfilePics/".$row['user_profile_picture']."' width='30px' height='30px'/>
                                        </div>
                                        <div class='user-feeds-post-upload-comments-name'>
                                
                                            <p>". $row['user_full_name'] . "</p>
                                
                                        </div>
                                        <form method='POST'>
                                            <div class='user-feeds-post-upload-comments-input'>
                                                <textarea name='user-comment' placeholder='Write a comment...' rows='4' cols='80' ></textarea>
                                            </div>
                                            <div class='user-feeds-post-upload-comments-submit'>
                                                <input type='hidden' name='parent_upload_id' value='".$showPostRow['upload_Id']."' />
                                                <button type='submit' name='submit-comment' class='submit-comment-btn'>Upload</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!--- comment section--->
                                    ";
                                    ///------------------------display comments------------------------///
                                    $comments="SELECT * FROM post_comments WHERE parent_upload_Id='".$showPostRow['upload_Id']."'";
                                    $commentsQuery= mysqli_query($conn, $comments);
                                    $commentsQueryCheck= mysqli_num_rows($commentsQuery);

                                    

                                    if($commentsQueryCheck>0){
                                        while($commentsQueryCheckRow=mysqli_fetch_assoc($commentsQuery)){
                                            $commentDetails= "SELECT signup_database.user_full_name, signup_database.user_profile_picture FROM signup_database INNER JOIN post_comments ON
                                            signup_database.user_Id = post_comments.user_Id WHERE post_comments.user_Id='".$commentsQueryCheckRow['user_Id']."'";
                                            $commentDetailsQuery=mysqli_query($conn,$commentDetails);
                                            $commentDetailsQueryRow=mysqli_fetch_assoc($commentDetailsQuery);

                                            ////-------------DELETE COMMENTS-------------/////
                                            if(isset($_GET['deleteCommentInFeedsPost'])){
                                                $deleteCommentIdInFeeds= $_GET['deleteCommentInFeedsPost'];
                                                $deleteCommentInFeed="DELETE FROM post_comments WHERE comment_Id='".$deleteCommentIdInFeeds."'";
                                                mysqli_query($conn, $deleteCommentInFeed);
                                                echo "<meta http-equiv='refresh' content='0;url=user-feeds.php'>";
                                            }
                                            echo "
                                                <div class='user-feeds-post-friend-comments'>
                                                    <div class='user-feeds-post-friend-comments-pfp'>
                                                        <img src='../../private/ProfilePics/".$commentDetailsQueryRow['user_profile_picture']."' width='30px' height='30px'/>
                                                    </div>
                                                    <div class='user-feeds-post-friend-comment-name'>
                                                        <p>". $commentDetailsQueryRow['user_full_name'] . "</p>
                                                    </div>
                                                    <div class='user-feeds-delete-comment'>
                                                        <button><a href='user-feeds.php?deleteCommentInFeedsPost=".$commentsQueryCheckRow['comment_Id']."'>
                                                        <img src='../Images/delete.png'  width='15px' height='15px'>
                                                        </a></button>
                                                    </div>
                                                    <div class='user-feeds-post-friend-comment-content'>
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
                        </div>
                    </div>
                ";
            }
        }
    
    ?>
    <script>
        var loadFile = function(event) {
	    var image = document.getElementById('output');
	    image.src = URL.createObjectURL(event.target.files[0]);
        };
        
        var img = document.getElementById("uploadedImg");
        img.onerror = function () { 
            this.style.display = "none";
        }

    </script>
    <script src="../script.js"></script>
</body>
</html>

        