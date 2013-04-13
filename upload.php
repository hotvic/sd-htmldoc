<?php

function checkUser(){
    include("database.php");

    $db = new Database();

    if ( $db->login_check($_GET["user"])['user'] ){
        echo "1";
    } else {
        echo "0";
    }
}
/*
 * Check for password, and return #status
 * #status:
 *  0: both password and username are incorrect, or error
 *  1: username correct but password incorrect
 *  2: both password and username are correct
 *  3: #status for blocked users (brute force protection)
 */
function checkPass(){
    include("database.php");

    $db = new Database();
    // Get variables
    $user = $_GET['user'];
    $pass = $_GET['pass'];
    // Finally check
    $sure = $db->login_check($user, $pass);

    if ( $sure != false ){
        if ( $sure['user'] == false && $sure['pass'] == false )
            echo '0';
        else if ( $sure['user'] && $sure['pass'] == false )
            echo '1';
        else if ( $sure['user'] && $sure['pass'] )
            echo '2';
        else if ( $sure['user'] == 'blocked' )
            echo '3';
    } else {
        echo '0';
    }
}


if ( isset($_GET["action"], $_GET["user"]) && ! isset($_GET["pass"]) ){
    $a = $_GET["action"];

    switch ( $a ){
        case "check":
            checkUser();
            break;
    }
} elseif ( isset($_GET["action"], $_GET["user"], $_GET["pass"]) ){
    $a = $_GET["action"];

    switch ( $a ){
        case "check":
            checkPass();
            break;
    }
}
?>
