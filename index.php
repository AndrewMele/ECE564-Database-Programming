<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'includes/process_login.php';
 
sec_session_start();
 ?>
 
<! DOCTYPE html>
<html>
<head>
    <title>INF Alliance Login</title>
    <script type="text/JavaScript" src="js/sha512.js"></script> 
    <script type="text/JavaScript" src="js/forms.js"></script>
    <link rel="stylesheet" type="text/css" href="css/login.css"></link>
</head>
<body>
    <div class="background"></div>
    <div class="container">
        <img src="img/logo.png">
        <form action="<?php esc_url($_SERVER["REQUEST_URI"]);?>" method="post" name="login_form">
            <input type="text" name="username" id="username" placeholder="Enter Username" <?php if(isset($_POST['username'])) {echo 'value="' . $_POST['username'] . '"';} ?>>
            <input type="password" name="password" id="password" placeholder="Enter Password">
            <p>
                <a class="register" href="register.php">Register</a> 
                <a href="#">Forget Password</a>
            </p>
            <input type="submit" value="Login" onclick="formhash(this.form, this.form.password,form.login_error);">
            <?php
                if (isset($_POST['login_error']))
                    echo '<p class="error">' . $_POST['login_error'] . '</p>';
            ?>
        </form>
    </div>     
</body>
</html>

