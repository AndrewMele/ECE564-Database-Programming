<?php

include_once 'config.php';

function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name 
    $secure = SECURE;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) 
    {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session 
    session_regenerate_id();    // regenerated the session, delete the old one. 
}

function login($username, $password, $mysqli) {
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare("SELECT username, password FROM Players WHERE username = ? LIMIT 1")) 
    {
        $stmt->bind_param('s', $username);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
 
        // get variables from result.
        $stmt->bind_result($username, $db_password);
        $stmt->fetch();
 
        if ($stmt->num_rows == 1) 
        {
            // If the user exists we check if the account is locked
            // from too many login attempts 
 
             // Check if the password in the database matches
             // the password the user submitted. We are using
             // the password_verify function to avoid timing attacks.
             if (password_verify($password, $db_password)) 
             {
                // Password is correct!
                // Get the user-agent string of the user.
                $user_browser = $_SERVER['HTTP_USER_AGENT'];
                // XSS protection as we might print this value
                $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
                $_SESSION['username'] = $username;
                $_SESSION['login_string'] = hash('sha512', $db_password . $user_browser);
                // Login successful.
                return true;
             } 
             else 
                return false;   //Password is incorrect
            
        } 
        else 
            return false;   //No such user exists
    }
}

function login_check($mysqli) {
    // Check if all session variables are set 
    if (isset($_SESSION['username'], $_SESSION['login_string'])) 
    {

        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
        if ($stmt = $mysqli->prepare("SELECT password FROM Players WHERE username = ? LIMIT 1")) 
        {
            // Bind "$username" to parameter. 
            $stmt->bind_param('s', $username);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) 
            {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
 
                if (hash_equals($login_check, $login_string) )
                    return true;  //Logged in!
                else 
                    return false; // Not logged in
            } 
            else 
                return false; // Not logged in
        } 
        else 
            return false; // Not logged in
    } 
    else  
        return false; // Not logged in
}

function esc_url($url) {
    
       if ('' == $url)
           return $url;
    
       $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
    
       $strip = array('%0d', '%0a', '%0D', '%0A');
       $url = (string) $url;
    
       $count = 1;
       while ($count)
           $url = str_replace($strip, '', $url, $count);
    
       $url = str_replace(';//', '://', $url);
    
       $url = htmlentities($url);
    
       $url = str_replace('&amp;', '&#038;', $url);
       $url = str_replace("'", '&#039;', $url);
    
       if ($url[0] !== '/')
           return '';   // We're only interested in relative links from $_SERVER['PHP_SELF']
       else 
           return $url;
}

function format_js_chart($data){
    foreach ($data as $K=>$V)
    {
        $titles[] = $K;
        $values[] = $V;
    }
    $titles = json_encode($titles);
    $values = json_encode($values);
    return array($titles,$values);
}

function home_stats($mysqli){
    $gather_count = get_stats('Gathering',$mysqli); 
    $crafting_count = get_stats('Crafting', $mysqli); 
    $farming_count = get_stats('Farming', $mysqli);
    $gather_tier = get_stats_tier('Gathering', $mysqli);
    $crafting_tier = get_stats_tier('Crafting', $mysqli);
    $farming_tier = get_stats_tier('Farming', $mysqli);
    $player_count = get_stats($_SESSION['username'], $mysqli);
    $player_tier = get_stats_tier($_SESSION['username'], $mysqli);
    return array($gather_count,$gather_tier,$crafting_count,$crafting_tier,$farming_count,$farming_tier,$player_count,$player_tier);
}

function get_stats($table, $mysqli){
    switch ($table)
    {
        case 'Gathering':
            $stone_count = query_db("SELECT COUNT(*) FROM Gathering WHERE resource = 'Stone Quarrier'", $mysqli);
            $wood_count = query_db("SELECT COUNT(*) FROM Gathering WHERE resource = 'Lumberjack'", $mysqli); 
            $ore_count = query_db("SELECT COUNT(*) FROM Gathering WHERE resource = 'Ore Miner'", $mysqli);
            $fiber_count = query_db("SELECT COUNT(*) FROM Gathering WHERE resource = 'Fiber Harvester'", $mysqli);
            $skin_count = query_db("SELECT COUNT(*) FROM Gathering WHERE resource = 'Animal Skinner'", $mysqli);
            return array('Stone Quarrier'=>$stone_count, 'Lumberjack'=>$wood_count, 'Ore Miner'=>$ore_count, 'Fiber Harvester'=>$fiber_count, 'Animal Skinner'=>$skin_count);
            break;
        case 'Crafting':
            $warrior_count = query_db("SELECT COUNT(*) FROM Crafting WHERE type = 'Warrior'", $mysqli);
            $hunter_count = query_db("SELECT COUNT(*) FROM Crafting WHERE type = 'Hunter'", $mysqli);
            $mage_count = query_db("SELECT COUNT(*) FROM Crafting WHERE type = 'Mage'", $mysqli);
            $gathering_count = query_db("SELECT COUNT(*) FROM Crafting WHERE type = 'Gathering'", $mysqli);
            return array('Warrior Forge'=>$warrior_count, 'Hunter Lodge'=>$hunter_count, 'Mage Tower'=>$mage_count, 'Gathering'=>$gathering_count);
            break;
        case 'Farming':
            $crop_count = query_db("SELECT COUNT(*) FROM Farming WHERE type = 'Crop'", $mysqli);
            $chef_count = query_db("SELECT COUNT(*) FROM Farming WHERE type = 'Chef'", $mysqli);
            $herb_count = query_db("SELECT COUNT(*) FROM Farming WHERE type = 'Herb'", $mysqli);
            $pasture_count = query_db("SELECT COUNT(*) FROM Farming WHERE type = 'Pasture'", $mysqli);
            return array('Crops'=>$crop_count, 'Chefs'=>$chef_count, 'Herbs'=>$herb_count, 'Pastures'=>$pasture_count);
            break;
        default:
            $gathering_count = query_db("SELECT COUNT(*) FROM Gathering WHERE player = '$table'", $mysqli);
            $crafting_count = query_db("SELECT COUNT(*) FROM Crafting WHERE player = '$table'", $mysqli);
            $farming_count = query_db("SELECT COUNT(*) FROM Farming WHERE player = '$table'", $mysqli);
            return array('Gathering'=>$gathering_count, 'Crafting'=>$crafting_count, 'Farming'=>$farming_count);
    }
}

function get_stats_tier($table, $mysqli){
    switch ($table)
    {
        case 'Gathering':
            $t8 = query_db("SELECT COUNT(*) FROM Gathering WHERE tier = 8", $mysqli);
            $t7 = query_db("SELECT COUNT(*) FROM Gathering WHERE tier = 7", $mysqli);
            $t6 = query_db("SELECT COUNT(*) FROM Gathering WHERE tier = 6", $mysqli);
            $t5 = query_db("SELECT COUNT(*) FROM Gathering WHERE tier = 5", $mysqli);
            $t4 = query_db("SELECT COUNT(*) FROM Gathering WHERE tier = 4", $mysqli);
            return array('Tier 8'=>$t8, 'Tier 7'=>$t7, 'Tier 6'=>$t6, 'Tier 5'=>$t5, 'Tier 4'=>$t4);
            break;
        case 'Crafting':
            $t8 = query_db("SELECT COUNT(*) FROM Crafting WHERE level = 100", $mysqli);
            $t7 = query_db("SELECT COUNT(*) FROM Crafting WHERE level >= 60 AND level < 100", $mysqli);
            $t6 = query_db("SELECT COUNT(*) FROM Crafting WHERE level >= 30 AND level < 60", $mysqli);
            $t5 = query_db("SELECT COUNT(*) FROM Crafting WHERE level >= 10 AND level < 30", $mysqli);
            $t4 = query_db("SELECT COUNT(*) FROM Crafting WHERE level < 10", $mysqli);
            return array('Tier 8'=>$t8, 'Tier 7'=>$t7, 'Tier 6'=>$t6, 'Tier 5'=>$t5, 'Tier 4'=>$t4);
            break;
        case 'Farming':
            $t8 = query_db("SELECT COUNT(*) FROM Farming WHERE level = 100", $mysqli);
            $t7 = query_db("SELECT COUNT(*) FROM Farming WHERE level >= 60 AND level < 100", $mysqli);
            $t6 = query_db("SELECT COUNT(*) FROM Farming WHERE level >= 30 AND level < 60", $mysqli);
            $t5 = query_db("SELECT COUNT(*) FROM Farming WHERE level >= 10 AND level < 30", $mysqli);
            $t4 = query_db("SELECT COUNT(*) FROM Farming WHERE level < 10", $mysqli);
            return array('Tier 8'=>$t8, 'Tier 7'=>$t7, 'Tier 6'=>$t6, 'Tier 5'=>$t5, 'Tier 4'=>$t4);
            break;
        default:
            $t8 = query_db("SELECT COUNT(*) FROM Gathering WHERE tier = 8 AND player = '$table'", $mysqli);
            $t8 += query_db("SELECT COUNT(*) FROM Crafting WHERE level = 100 AND player = '$table'", $mysqli);
            $t8 += query_db("SELECT COUNT(*) FROM Farming WHERE level = 100 AND player = '$table'", $mysqli);
        
            $t7 = query_db("SELECT COUNT(*) FROM Gathering WHERE tier = 7 AND player = '$table'", $mysqli);
            $t7 += query_db("SELECT COUNT(*) FROM Crafting WHERE level >= 60 AND level < 100 AND player = '$table'", $mysqli);
            $t7 += query_db("SELECT COUNT(*) FROM Farming WHERE level >= 60 AND level < 100 AND player = '$table'", $mysqli);

            $t6 = query_db("SELECT COUNT(*) FROM Gathering WHERE tier = 6 AND player = '$table'", $mysqli);
            $t6 += query_db("SELECT COUNT(*) FROM Crafting WHERE level >= 30 AND level < 60 AND player = '$table'", $mysqli);
            $t6 += query_db("SELECT COUNT(*) FROM Farming WHERE level >= 30 AND level < 60 AND player = '$table'", $mysqli);

            $t5 = query_db("SELECT COUNT(*) FROM Gathering WHERE tier = 5 AND player = '$table'", $mysqli);
            $t5 += query_db("SELECT COUNT(*) FROM Crafting WHERE level >= 10 AND level < 30 AND player = '$table'", $mysqli);
            $t5 += query_db("SELECT COUNT(*) FROM Farming WHERE level >= 10 AND level < 30 AND player = '$table'", $mysqli);

            $t4 = query_db("SELECT COUNT(*) FROM Gathering WHERE tier = 4 AND player = '$table'", $mysqli);
            $t4 += query_db("SELECT COUNT(*) FROM Crafting WHERE level < 10 AND player = '$table'", $mysqli);
            $t4 += query_db("SELECT COUNT(*) FROM Farming WHERE level < 10 AND player = '$table'", $mysqli);

            return array('Tier 8'=>$t8, 'Tier 7'=>$t7, 'Tier 6'=>$t6, 'Tier 5'=>$t5, 'Tier 4'=>$t4);
    }
}

function query_db($query, $mysqli){
    if ($stmt = $mysqli->prepare($query))
    {
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($result);
        $stmt->fetch();
        return $result;
    }
    else
        print(mysqli_stmt_error_list($stmt));
        echo 'Some Error Occured...';
        return false;
}

function GenerateHTMLTable($name, $mysqli){
    $table = $mysqli->query("SELECT * FROM $name");
    echo '<table id="' . $name . 'Table"><tr>';
    while($field = $table->fetch_field())
    {
        $field_name = ucfirst($field->name);
        echo "<th>$field_name</th>";
    }
    echo '</tr>';
    while($row = $table->fetch_row())
    {
        echo "<tr>";
        for ($index = 0; $index < $table->field_count; $index++)
        echo "<td>$row[$index]</td>";
        echo "</tr>";
    } 
    echo '</table>';
}










