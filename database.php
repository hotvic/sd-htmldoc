<?php

// Configuration
define('DB_USER', 'user');
define('DB_PASS', 'pass');
define('DB_HOST', 'localhost');
define('DB_BASE', 'sd-hmldoc');
// End Configuration

// Main database class
class Database {
    private $mysqli;

    function __construct() {
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_BASE);

        if (! $this->mysqli ) return false;
    }

    /*
     * Check if login's correct.
     * pass = false: it'll check only if user is correct.
     */
    // TODO:
    //  write brute force block.
    function login_check($user, $pass = false) {
        if ( $stmt = $this->mysqli->prepare("SELECT id, password, salt FROM authors WHERE username = ? LIMIT 1") ){
            $stmt->bind_param('s', $user);
            $stmt->execute();
            $stmt->bind_result($id, $password, $salt);

            if ( $stmt->fetch() ) {
                if ( $pass != false ){
                    if ( hash('sha512', $pass . $salt) == $password )
                        return array('user' => true, 'pass' => true);
                    else
                        return array('user' => true, 'pass' => false);
                } else {
                    return array('user' => true, 'pass' => false);
                }
            } else {
                return false;
            }
            $stmt->close();

        } else {
            return false;
        }
    }

    /*
     * Add a user to database
     * Note: ID is auto-increment
     */
    function add_user($u){
        $error = false;
        $insert = "INSERT INTO authors(username, email, realname, password, salt) VALUES(?, ?, ?, ?, ?)";

        if (! isset($u['username'], $u["email"], $u["realname"], $u["password"], $u["salt"]) ) $error = true;

        if ( $error ) return false;

        if ( $stmt = $this->mysqli->prepare($insert) ){
            $stmt->bind_param('sssss', $u['username'], $u['email'],
                        $u['realname'], $u['password'], $u['salt']);
            $stmt->execute();
            $stmt->close();
        } else {
            return false;
        }
        return true;
    }
    /*
     * Query for user's info
     * return it in an assosiative array, or false for error
     */
    function query_user($user, $pass) {
        $check = $this->login_check($user, $pass);
        if (! $check["user"] &&! $check["user"] ) return false;

        $query = "SELECT id, username, email, realname FROM authors WHERE username = ?";

        if ( $stmt = $this->mysqli->prepare($query) ) {
            $stmt->bind_param('s', $user);
            $stmt->execute();
            $stmt->bind_result($id, $username, $email, $realname);

            if ( $stmt->fetch() ) {
                $arr = array('id' => $id, 'username' => $username,
                            'email' => $email, 'realname' => $realname);
                return $arr;
            }
        }
        return false;
    }
}
?>
