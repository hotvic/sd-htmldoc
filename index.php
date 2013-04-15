<?php
// Set timezone, because some servers aren't configured this on php
date_default_timezone_set("UTC");

// Includes
include("database.php");

// Files and folders in this array will don't be listed
$excludes = array(".", "..", "js", "imgs");

function show_head() {
    if ( file_exists("index.php.head") ){
        $head = file_get_contents("index.php.head");

        echo $head;
    }
}

function show_foot() {
    if ( file_exists("index.php.foot") ){
        $foot = file_get_contents("index.php.foot");

        echo $foot;
    }
}

function get_array_for($entry) {
    if ( file_exists($entry . "/author" ))
        $afile = file_get_contents($entry . "/author");

    if ( isset($afile) ){
        $arr  = explode(":", explode("\n", $afile)[0]);
        return array('tutorial' => $arr[0], 'author' => $arr[1]);
    }

    return false;
}

function echo_entry($entry) {
    if (! isset($entry["author"], $entry["folder"], $entry["tutorial"],
                $entry["mtime"], $entry["date"]) ) return false;

    echo "        <tr>\n";
    echo "            <td><a href=\"${entry["folder"]}/${entry["folder"]}.html\">${entry["tutorial"]}</a></td>\n";
    echo "            <td><a href=\"${entry["folder"]}\">${entry["folder"]}</a></td>\n";
    echo "            <td><a href=\"index.php?date=${entry["mtime"]}\">${entry["date"]}</a></td>\n";
    echo "            <td><a href=\"index.php?author=${entry["author"]}\">${entry["author"]}</a></td>\n";
    echo "        </tr>\n";
}

function list_all() {
    global $excludes;
    $db = new Database();

    if ( $handle = opendir(".") ){
        while ( false !== ($entry = readdir($handle)) ){
            if ( ! in_array($entry, $excludes) && is_dir($entry) ){
                $dbtuto   = $db->query_tuto("folder", $entry);
                $tutorial = $dbtuto ? $dbtuto : get_array_for($entry);
                $mtime    = filemtime("$entry/$entry.html");
                $date     = date("F d Y H:i", $mtime);

                $arr = array('author' => $tutorial['author'], 'tutorial' => $tutorial['tutorial'],
                            'folder' => $entry, 'mtime' => $mtime, 'date' => $date);
                echo_entry($arr);
            }
        }
    closedir($handle);
    }
}

function list_sorted($sort) {
    global $excludes;
    $db    = new Database();
    $tutos = array();

    if ( $handle = opendir(".") ){
        while ( false !== ($entry = readdir($handle)) ){
            if (! in_array($entry, $excludes) && is_dir($entry) ){
                $dbtuto   = $db->query_tuto("folder", $entry);
                $tutorial = $dbtuto ? $dbtuto : get_array_for($entry);
                $date     = date("F d Y H:i", filemtime("$entry/$entry.html"));
                $mtime    = filemtime("$entry/$entry.html");

                switch ($sort){
                    case "tutos":
                        $tutos += array($tutorial['tutorial'] =>
                                    array("tutorial" => $tutorial["tutorial"],
                                        "folder" => $entry,
                                        "date" => $date,
                                        "mtime" => $mtime,
                                        "author" => $tutorial['author']));
                        break;
                    case "files":
                        $tutos += array($entry =>
                                    array("folder" => $entry,
                                        "tutorial" => $tutorial['tutorial'],
                                        "date" => $date,
                                        "mtime" => $mtime,
                                        "author" => $tutorial['author']));
                        break;
                    case "autor":
                        $tutos += array("${tutorial['author']} $entry" =>
                                    array("tutorial" => $tutorial['tutorial'],
                                        "folder" => $entry,
                                        "date" => $date,
                                        "mtime" => $mtime,
                                        "author" => $tutorial["author"]));
                        break;
                }
            }
        }
    }
    ksort($tutos, SORT_NATURAL | SORT_FLAG_CASE);

    foreach ($tutos as $key => $val) {
        echo_entry($val);
    }
}
// TODO:
//  Write this function...
function list_by_author($athor) {

}

if ( ! isset($_GET["sort"]) && ! isset($_GET["author"]) ) {
    show_head();
    list_all();
    show_foot();
} elseif ( isset($_GET["sort"]) ) {
    $sort = $_GET["sort"];

    show_head();
    list_sorted($sort);
    show_foot();
} elseif ( isset($_GET["author"]) ) {
    echo "Database functions are not implemented yet!\n";
    echo "<br>\nSorry for this inconvience.";
}
