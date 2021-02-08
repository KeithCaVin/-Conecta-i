<?php
    include '../../private/connect-to-database.php';
    include '../../private/functions.php';
    session_start();

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
    <!------------ EDIT POST FUNCTION ------------>
    <?php
        $sqlPost= "SELECT * FROM `user_upload` WHERE `user_upload`.`user_Id`=". $_SESSION["userid"]." ORDER BY `user_upload`.`upload_time` DESC";
        $showPostQuery=mysqli_query($conn, $sqlPost);
        $showPostRow=mysqli_fetch_assoc($showPostQuery);
        
        if(isset($_POST['submit-edited-post'])){
            $newCaption=$_POST['edit-user-caption'];
            $sqlUpdatePost="UPDATE user_upload SET upload_text='$newCaption' WHERE upload_Id='".$_GET['post']."'";
            mysqli_query($conn,$sqlUpdatePost);
            header("Location: user-feeds.php");
        }
    ?>
    <div class="form-container">
        <form method="POST" enctype='multipart/form-data'>
            <textarea name="edit-user-caption" rows="5" cols="80"><?php echo $showPostRow['upload_text'] ?></textarea>
            <button type="submit" name="submit-edited-post">Save</button>
            
        </form>
    </div>
</body>
</html>
