<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conectați | Log in</title>
    <link rel="stylesheet" href="../SCSS/Main.css">
</head>
<body>
    <div class="signup-header-container">
        <header><p>Conectați</p></header>
        <a href="../index.php">Sign-up</a>
    </div>

    <div class="login-main-container">
        <div class="login-form-container">
            <h1>Log-in</h1>
            <p>Don't have an account yet? &nbsp;<a href="../index.php"></br>Signup</a> Here!</p>
            <form action="../../private/sign-in.php" method="POST">
                <input type="text" name="user_login_email" placeholder="Email">
                <input type="password" name="user_login_password" placeholder="Password">
                <button type="submit" name="login_button" class="login-btn">Log-in</button>
            </form>
            <?php
                if (isset($_GET["error"])) {
                    if ($_GET["error"]== "emptyinput") {
                        echo "<p style='position: absolute; left:100px; top:300px; color:red;'> Please fill out all the fields </p>";
                    }
                    if ($_GET["error"]== "unkownaccount") {
                        echo "<p style='position: absolute; left:110px; top:300px; color:red;'> Account does not exist! </p>";
                    }
                    else if ($_GET["error"]== "wrongpassword") {
                        echo "<p style='position: absolute; left:120px; top:300px; color:red;'> Password Incorrect </p>";
                    }
                    
                }
            ?>
        </div>
    </div>


    <div class="login-description-container">
        <div class="login-description-inner-container">
            <h2>Welcome to Conectați</h2>
            <div class="login-content">
                <p>Conectati is a platform where people across the globe connect with each other!</p>
                <p>Share your photos, videos and stories in Connecti for your friends to see. Register now in order to start making connections with people around the world!</p>
            </div>
            <img src="../Images/Connecti.png" height="550px" width="550px">
        </div>
    </div>
</body>
</html>