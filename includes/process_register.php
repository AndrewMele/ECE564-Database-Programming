<?php
include_once 'db_connect.php';
include_once 'config.php';
 
$error_msg = "";
if (isset($_POST['username'], $_POST['guild'], $_POST['p'])) 
{
    // Sanitize and validate the data passed in
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $guild = filter_input(INPUT_POST, 'guild', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);

    if (strlen($password) != 128) 
    {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }
 
    // Username validity and password validity have been checked client side.
    // This should be adequate as nobody gains any advantage from
    // breaking these rules.
    //
 
    $prep_stmt = "SELECT * FROM Players WHERE username = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
 
   // check existing username  
    if ($stmt) 
    {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
 
        if ($stmt->num_rows == 1) 
        {
            // A user with this name already exists
            $error_msg .= '<p class="error">A user with this username already exists.</p>';
                        $stmt->close();
        }
    } 
    else 
    {
        $error_msg .= '<p class="error">Database error Line 36</p>';
                $stmt->close();
    }
 
    // check existing guild
    $prep_stmt = "SELECT * FROM Guilds WHERE name = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
 
    if ($stmt) 
    {
        $stmt->bind_param('s', $guild);
        $stmt->execute();
        $stmt->store_result();
 
                if ($stmt->num_rows != 1) 
                {
                        // Guild does not exist within the alliance
                        $error_msg .= '<p class="error">The guild specifed is not currently within the INF alliance</p>';
                        $stmt->close();
                }
        } 
        else 
        {
                $error_msg .= '<p class="error">Database error line 59</p>';
                $stmt->close();
        }
 
    // TODO: 
    // We'll also have to account for the situation where the user doesn't have
    // rights to do registration, by checking what type of user is attempting to
    // perform the operation.
 
    if (empty($error_msg)) 
    {
        // Create hashed password using the password_hash function.
        // This function salts it with a random salt and can be verified with
        // the password_verify function.
        $password = password_hash($password, PASSWORD_BCRYPT);
 
        // Insert the new user into the database 
        if ($insert_stmt = $mysqli->prepare("INSERT INTO Players (username, guild, password) VALUES (?, ?, ?)")) 
        {
            $insert_stmt->bind_param('sss', $username, $guild, $password);
            // Execute the prepared query.
            if (! $insert_stmt->execute())
                header('Location: ../error.php?err=Registration failure: INSERT');
        }
        $_SESSION['btn'] = 1;
    }
}
?>