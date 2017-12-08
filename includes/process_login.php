<?php
include_once 'db_connect.php';
include_once 'functions.php';
 
sec_session_start(); // Our custom secure way of starting a PHP session.
 
if (isset($_POST['username'], $_POST['p'])) 
{
    $username = $_POST['username'];
    $password = $_POST['p']; // The hashed password.
 
    if (login($username, $password, $mysqli) == true) 
        header('Location: app.php');  // Login success 
    else
    { 
        $_POST['login_error'] = "Incorrect Username or Password";  
    }
} 
 

