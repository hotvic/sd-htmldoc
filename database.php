<?php
class UserDB {
    protected $file = FALSE;
    protected $open = FALSE;

    function opendb($dbfilename){
        $this->file = fopen($dbfilename, "c+");
        if ( $this->file == FALSE ) die("Couldn't Open Database file, Sorry!");
        $this->open = TRUE;
    }
    function closedb(){
        if ( $this->open ) fclose($this->file);
        else die("Database is currently not open, cannot close then!");
    }
    /*
     * +--------------+--------------+
     * | 0 = ID       | 1 = nick     |
     * +--------------+--------------+
     * | 2 = password | 3 = realname |
     * +-----------------------------+
     * | 4 = number of posts         |
     * +-----------------------------+
     */
    function queryUser($where, $who = 0){
        $user = FALSE;

        while ( ! feof($this->file) ){
            $line = fgets($this->file);

            if ( $line[0] == "#" || $line[0] == "\n" ) continue;

            $u = explode(":", $line);

            if ( $u[$who] == $where ){
                $user = $u;
                rewind($this->file);
                break;
            }
        }
        return $user;
    }
    function addUser($user){
        $id = isset($user["id"]) ? $user["id"] : $this->getnewID();
        $fa = array($id, $user["nick"], $user["pass"],
                    $user["real"], $user["num"]);
        $u = implode(":", $fa);

        fseek($this->file, 0, SEEK_END);
        fwrite($this->file, $u . PHP_EOL);
        rewind($this->file);
    }
    function getnewID(){
        $last = 0;

        while ( ! feof($this->file) ){
            $line = fgets($this->file);

            if ( $line[0] == "#" || $line[0] == "\n" ) continue;

            $cur = (int) explode(":", $line)[0];
            $last = $cur < $last ? $last : $cur;
        }
        rewind($this->file);

        return $last < 1001 ? 1001 : $last + 1;
    }
}
?>
