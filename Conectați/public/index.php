<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conectați | Sign Up</title>
    <link rel="stylesheet" href="SCSS/Main.css">
</head>
<body>
    <!--------------THIS IS FOR THE SIGN UP PAGE------------------->
    <div class="signup-header-container">
        <header><p>Conectați</p></header>
        <a href="Pages/login.php">Log-in</a>
    </div>
    <div class="signup-main-container">
        <div class="signup-form-container">
            <h1>Sign Up</h1>
            <form action="../private/sign-up.php" method="POST">
                <input type="text" name="user_full_name" placeholder="Full Name">
                <input type="text" name="user_email" placeholder="Email">
                <input type="password" name="user_password" placeholder="Password">
                <input type="password" name="user_password_repeat" placeholder="Confirm Password">
                <input type="text" name="user_contact_number" placeholder="Contact Number">
                <input type="date" name="user_date_of_birth" placeholder="Date of Birth">
                <input type="text" name="user_address" placeholder="Address">
                <input type="text" name="user_age" placeholder="Age">
                <input type="text" name="user_gender" placeholder="Gender">
                <button type="submit" name="signup_submit" class="signup-btn">Sign Up</button>
                
                
            </form>
            <p>Already have an account? &nbsp;<a href="Pages/login.php"></br>Log-in</a> Here!</p>
            <?php
                if (isset($_GET["error"])) {
                    if ($_GET["error"]== "EmptyInput") {
                        echo "<p style='position: absolute; top:610px; left: 100px; color:red;'> Please fill out all the fields </p>";
                    }
                    else if ($_GET["error"]== "none") {
                        echo "<p style='position: absolute; top:610px; left: 50px; color:red;'> Sign up successful! Thank you for signing up! </p>";
                    }
                    else if ($_GET["error"]== "EmailAlreadyExists") {
                        echo "<p style='position: absolute; top:610px; left: 110px; color:red;'> Email Already Exists! </p>";
                    }
                }
            ?>
            
        </div>
    </div>

    <div class="signup-description-container">
        <div class="signup-description-inner-container">
            <h2>Welcome to Conectați</h2>
            <div class="signup-content">
                <p>Conectati is a platform where people across the globe connect with each other!</p>
                <p>Share your photos, videos and stories in Connecti for your friends to see. Register now in order to start making connections with people around the world!</p>
            </div>
            <img src="Images/Connecti.png" height="550px" width="550px">
        </div>
    </div>
    
</body>
</html>
