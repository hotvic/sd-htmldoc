<?php

// Configuration
$db_user = 'user';
$db_pass = 'pass';
$db_host = 'localhost';
$db_base = 'sd-hmldoc';
// End Configuration

// Global function(s)
function sec_session_start() {
    $session_name = 'sd-htmldoc_login';
    $secure       = false;
    $httponly     = true;

    ini_set('session.use_only_cookies', 1);
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
    session_name($session_name);
    session_start();
    session_regenerate_id(true);
}

// Main database class
class Database {
    private $mysqli;

    function __construct() {
        $this->mysqli = new mysqli($db_host, $db_user, $db_base);

        if (! $this->mysqli ) return false;
    }

    function login_check($user, $pass = false) {
        if ( $stmt = $this->mysqli->prepare("SELECT id, password, salt FROM authors WHERE username = ? LIMIT 1") ){
            $stmt->bind_param('s', $user);
            $stmt->execute();
            $stmt->bind_result($id, $password, $salt);
            $stmt->fetch();

            if ( $smtp->num_rows == 1 ){

            } else {
                return false;
            }
            $stmt->close();

        } else {
            return false;
        }
    }
}
?>
