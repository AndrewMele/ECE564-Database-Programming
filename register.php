<?php
include_once 'includes/process_register.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>INF Alliance Registration Form</title>
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script>
        <link rel="stylesheet" href="css/register.css"/>
    </head>
    <body>
        <?php
        if (!empty($error_msg)) {
            $_POST['e'] .= $error_msg;
        }
        ?>
        <div class="background"></div>
        <div class="container">
            <img src="img/logo.png">
            <?php
                if(!isset($_SESSION['btn'])) 
                {
                    echo '<form action="' . esc_url($_SERVER["REQUEST_URI"]) . '" method="post" name="registration_form">
                    <input type="hidden" name="e" id="e">
                    <input class="user" type="text" name="username" id="username" placeholder="Enter Username"'; 
                    if(isset($_POST['username'])) 
                        echo 'value="' . $_POST['username'] . '"';
                    echo '><input class="guild" type="text" name="guild" id="guild" placeholder="Enter Guild"';
                    if (isset($_POST['guild']))
                        echo 'value="' . $_POST['guild'] . '"';
                    echo '><input type="password" name="password" id="password" placeholder="Enter Password">
                    <input type="password" name="confirmpwd" id="confirmpwd" placeholder="Confirm Password">
                    <input type="submit" name="btn" value="Register" onclick="return regformhash(this.form, this.form.username, this.form.guild, this.form.password, this.form.confirmpwd, this.form.e);">
                    ';
                    if (isset($_POST['e']))
                        echo  '<p class="error">' . $_POST['e'] . '</p>';
                    echo '</form>';
                }
                else
                {
                    echo '<h1>Registration Successful!</h1>
                    <p>Your account will be reviewed shortly, and you will recieve a notification via Discord when your account has been activated.</p>
                    <p>Thank you for registering your account on the INF Alliance website!</p>'; 
                 } 
            ?>
            
            <a href="index.php">Go Back</a>
        </div>
    </body>
</html>