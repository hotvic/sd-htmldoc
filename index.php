<?php
// Set timezone, because some servers aren't configured this on php
date_default_timezone_set("UTC");

// Files and folders in this array will don't be listed
$excludes = array(".", "..", ".htaccess", "index.php", "index.php.head", "index.php.foot", 
                    "upload.php", "database.php", "db", "js", "imgs", "send.html");

function show_head(){
    if ( file_exists("index.php.head") ){
        $head = file_get_contents("index.php.head");

        echo $head;
    }
}

function show_foot(){
    if ( file_exists("index.php.foot") ){
        $foot = file_get_contents("index.php.foot");

        echo $foot;
    }
}

function get_array_for($entry){
    $return = false;

    if ( file_exists($entry . "/author" ))
        $afile = file_get_contents($entry . "/author");

    if ( isset($afile) ){
        $temp = explode("\n", $afile);
        $return = explode(":", $temp[0]);
    }

    return $return;
}

function list_all(){
    global $excludes;

    if ( $handle = opendir(".") ){
        while ( false !== ($entry = readdir($handle)) ){
            if ( ! in_array($entry, $excludes) ){
                $tutorial = get_array_for($entry);
                $date = date("F d Y H:i", filemtime("$entry/$entry.html"));
                $mtime = filemtime("$entry/$entry.html");

                echo "        <tr>\n";
                echo "            <td><a href=\"$entry/$entry.html\">$tutorial[0]</a></td>\n";
                echo "            <td><a href=\"$entry\">$entry</a></td>\n";
                echo "            <td><a href=\"index.php?date=$mtime\">$date</a></td>\n";
                echo "            <td><a href=\"index.php?author=$tutorial[1]\">$tutorial[1]</a></td>\n";
                echo "        </tr>\n";
            }
        }
    closedir($handle);
    }
}

function list_sorted($sort){
    global $excludes;
    $tutos = array();

    if ( $handle = opendir(".") ){
        while ( false !== ($entry = readdir($handle)) ){
            if ( ! in_array($entry, $excludes) ){
                $tutorial = get_array_for($entry);
                $date = date("F d Y H:i", filemtime("$entry/$entry.html"));
                $mtime = filemtime("$entry/$entry.html");

                switch ($sort){
                    case "tutos":
                        $tutos += array($tutorial[0] =>
                                    array("folder" => $entry,
                                        "date" => $date,
                                        "mtime" => $mtime,
                                        "author" => $tutorial[1]));
                        break;
                    case "files":
                        $tutos += array($entry =>
                                    array("tutorial" => $tutorial[0],
                                        "date" => $date,
                                        "mtime" => $mtime,
                                        "author" => $tutorial[1]));
                        break;
                    case "autor":
                        $tutos += array($tutorial[1] =>
                                    array("tutorial" => $tutorial[0],
                                        "folder" => $entry,
                                        "date" => $date,
                                        "mtime" => $mtime));
                        break;
                }
            }
        }
    }
    asort($tutos);

    $tutorial = false;
    $folder   = false;
    $date     = false;
    $mtime    = false;
    $author   = false;

    foreach ($tutos as $key => $val){
        switch ($sort){
            case "tutos":
                $tutorial = $key;
                $folder   = $val["folder"];
                $date     = $val["date"];
                $mtime    = $val["mtime"];
                $author   = $val["author"];
                break;
            case "files":
                $tutorial = $val["tutorial"];
                $folder   = $key;
                $date     = $val["date"];
                $mtime    = $val["mtime"];
                $author   = $val["author"];
                break;
            case "autor":
                $tutorial = $val["tutorial"];
                $folder   = $val["folder"];
                $date     = $val["date"];
                $mtime    = $val["mtime"];
                $author   = $key;
                break;
        }
        echo "        <tr>\n";
        echo "            <td><a href=\"$folder/$folder.html\">$tutorial</a></td>\n";
        echo "            <td><a href=\"$folder\">$folder</a></td>\n";
        echo "            <td><a href=\"index.php?date=$$mtime\">$date</a></td>\n";
        echo "            <td><a href=\"index.php?author=$author\">$author</a></td>\n";
        echo "        </tr>\n";
    }
}

if ( ! isset($_GET["sort"]) && ! isset($_GET["author"]) ){
    show_head();
    list_all();
    show_foot();
} elseif ( isset($_GET["sort"]) ){
    $sort = $_GET["sort"];

    show_head();
    list_sorted($sort);
    show_foot();
} elseif ( isset($_GET["author"]) ){
    echo "Database functions are not implemented yet!\n";
    echo "<br>\nSorry for this inconvience.";
}
