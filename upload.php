<?php
// HTML Head
$head = <<<HTML
<html>
<head>
    <title>Access deined!</title>
    <script type="text/javascript">
        setTimeout(function() {
            location.href = "send.html";
        }, 5000);
    </script>
</head>
<body>
HTML;
// Error Message!
$errmsg = <<<HTML
    <b>Error:</b> This page must be requested by internal scripts!
    <br>
    <a href="send.html">Redirecting...</a>
HTML;
// HTML footer
$foot = <<<HTML
</body>
</html>
HTML;

function error() {
    global $head, $foot, $errmsg;
    echo $head   . "\n";
    echo $errmsg . "\n";
    echo $foot   . "\n";
}
/*
 * Check for username, and return #status
 * #status:
 *  0: Incorrect username
 *  1: Correct username
 *  3: #status for blocked users (brute force protection)
 */
// TODO:
//  Write brute force protection check
function checkUser() {
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
function checkPass() {
    include("database.php");

    $db = new Database();
    // Get variables
    $user = $_GET['user'];
    $pass = $_GET['pass'];
    // Finally check
    $sure = $db->login_check($user, $pass);

    if ( $sure != false ) {
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

function upload() {
    include("database.php");
    $db = new Database();
    // Missing POST fields ?
    $mpost = false;

    if (! isset($_POST["tuto"], $_POST["user"], $_POST["pwd"], $_FILES["file"], $_POST["details"]) ) $mpost = true;
    if ( $mpost ) {
        error();
        return false;
    }

    $login = $db->query_user($_POST["user"], $_POST["pwd"]);

    if ( $login ){
        $r_hash   = md5(date('r', time()));
        $user     = $_POST["user"];
        $email    = $login["email"];
        $tuto     = $_POST["tuto"];
        $file     = $_FILES["file"];
        $details  = $_POST["details"];
        $to       = "Administrador <sodica@so-dicas.info>";
        $headers  = array("From: $email", 'Content-Type: multipart/mixed; boundary="PHP-mixed-' . $r_hash . '"');
        $subject  = "Moderal tutorial: $tuto";
        $attach   = chunk_split(base64_encode(file_get_contents($file["tmp_name"])));
        // Start output buffering for mail message
        ob_start();
?>
--PHP-mixed-<?php echo $r_hash; ?>

Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $r_hash; ?>"

--PHP-alt-<?php echo $r_hash; ?>

Content-Type: text/html; charset="utf-8"
Content-Transfer-Encoding: 7bit

<h3>Moderar Tutorial HTML: <?php echo $tuto; ?></h3>
<br>
<b>Autor:</b> <?php echo $user; ?>
<br>
<b>Detalhes Adicionais:</b> <?php echo $details; ?>

--PHP-alt-<?php echo $r_hash; ?>--

--PHP-mixed-<?php echo $r_hash; ?>

Content-Type: <?php echo $file["type"]; ?>; name="<?php echo $file["name"]; ?>"
Content-Transfer-Encoding: base64
Content-Disposition: attachment

<?php echo $attach; ?>

--PHP-mixed-<?php echo $r_hash; ?>--

<?php
        $message = ob_get_clean();
        $r = mail($to, $subject, $message, implode("\n", $headers));
        if ( $r )
            echo "Tutorial enviado!\n<br>\nAguardando moderação";
        else
            echo "Erro ao enviar tutorial!\n<br>\nTente novamente";
    } else {
        echo "Authentication failed!";
    }
}

if ( isset($_GET["action"]) ) {
    $a = $_GET["action"];
    switch ( $a ) {
        case "check":
            if ( isset($_GET["user"], $_GET["pass"]) )
                checkPass();
            elseif ( isset($_GET["user"]) &&! isset($_GET["pass"]) )
                checkUser();
            break;
        case "upload":
            upload();
            break;
        default:
            error();
            break;
    }
} else {
    error();
}
?>
