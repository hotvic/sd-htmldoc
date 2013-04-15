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

    // --authors--
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
            $stmt->close();
        }
        return false;
    }
    // --tutorials--
    /*
     * Add a tutorial for database
     * $tuto is an array of tutorial details
     */
    function add_tuto($tuto) {
        if (! isset($tuto["uid"], $tuto["folder"], $tuto["title"]) ) return false;

        $insert = "INSERT INTO tutorials(uid, folder, title) VALUES(?, ?, ?)";

        if ( $stmt = $this->mysqli->prepare($insert) ) {
            $stmt->bind_param('iss', $tuto["uid"], $tuto["folder"], $tuto["title"]);
            $stmt->execute();

            if ( $stmt->affected_rows() ) {
                $stmt->close();
            } else {
                $stmt->close();
            }
            $stmt->close();
        }
        return false;
    }
    /*
     * Return an assosiative array of tutorial filtered by "$by"
     * $by:
     *  possible values are: "id", "uid", "folder", "title"
     */
    function query_tuto($by, $where) {
        if (! in_array($by, array("id", "uid", "folder", "title")) ) return false;

        $query = "SELECT * FROM tutorials WHERE $by = ?";
        $type  = is_int($by) ? 'i' : 's';
        
        if ( $stmt = $this->mysqli->prepare($query) ) {
            $stmt->bind_param($type, $where);
            $stmt->execute();
            $stmt->bind_result($id, $uid, $folder, $title);

            if ( $stmt->fetch() ){
                $arr = array('id' => $id, 'uid' => $uid,
                            'folder' => $folder, 'title' => $title);
                return $arr;
            } else {
                return false;
            }
            $stmt->close();
        }
    }
    function get_all($limit = false) {
        $return = array();
        if ( $limit && is_int($limit) )
            $query = "SELECT * FROM tutorials LIMIT $limit";
        else
            $query = "SELECT * FROM tutorials";

        if ( $stmt = $this->mysqli->prepare($query) ) {
            $stmt->execute();
            $result = $stmt->get_result();

            while ( $tmp = $result->fetch_assoc() ) {
                $return[] = $tmp;
            }
            $stmt->close();

            return $return;
        } else {
            return false;
        }
    }
}
?>
