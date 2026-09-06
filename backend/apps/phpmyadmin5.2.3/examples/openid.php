<?php
/**
 * Single signon for phpMyAdmin using OpenID
 *
 * This is just example how to use single signon with phpMyAdmin, it is
 * not intended to be perfect code and look, only shows how you can
 * integrate this functionality in your application.
 *
 * It uses OpenID pear package, see https://pear.php.net/package/OpenID
 *
 * User first authenticates using OpenID and based on content of $AUTH_MAP
 * the login information is passed to phpMyAdmin in session data.
 */

declare(strict_types=1);

if (false === @include_once 'OpenID/RelyingParty.php') {
    exit;
}


$secure_cookie = false;

/**
 * Map of authenticated users to MySQL user/password pairs.
 */
$AUTH_MAP = [
    'https://launchpad.net/~username' => [
        'user' => 'root',
        'password' => '',
    ],
];

/**
 * Simple function to show HTML page with given content.
 *
 * @param string $contents Content to include in page
 */
function Show_page($contents): void
{
    header('Content-Type: text/html; charset=utf-8');

    echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
    echo '<!DOCTYPE HTML>
<html lang="en" dir="ltr">
<head>
<link rel="icon" href="../favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
<meta charset="utf-8">
<title>phpMyAdmin OpenID signon example</title>
</head>
<body>';

    if (isset($_SESSION['PMA_single_signon_error_message'])) {
        echo '<p class="error">' . $_SESSION['PMA_single_signon_message'] . '</p>';
        unset($_SESSION['PMA_single_signon_message']);
    }

    echo $contents;
    echo '</body></html>';
}

/**
 * Display error and exit
 *
 * @param Exception $e Exception object
 */
function Die_error($e): void
{
    $contents = "<div class='relyingparty_results'>\n";
    $contents .= '<pre>' . htmlspecialchars($e->getMessage()) . "</pre>\n";
    $contents .= "</div class='relyingparty_results'>";
    Show_page($contents);
    exit;
}


session_set_cookie_params(0, '/', '', $secure_cookie, true);

$session_name = 'SignonSession';
session_name($session_name);
@session_start();
$base = 'http';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $base .= 's';
}

$base .= '://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'];

$realm = $base . '/';
$returnTo = $base . dirname($_SERVER['PHP_SELF']);
if ($returnTo[strlen($returnTo) - 1] !== '/') {
    $returnTo .= '/';
}

$returnTo .= 'openid.php';


if ((! count($_GET) && ! count($_POST)) || isset($_GET['phpMyAdmin'])) {
    
    $content = '<form action="openid.php" method="post">
OpenID: <input type="text" name="identifier"><br>
<input type="submit" name="start">
</form>';
    Show_page($content);
    exit;
}


$identifier = null;
if (isset($_POST['identifier']) && is_string($_POST['identifier'])) {
    $identifier = $_POST['identifier'];
} elseif (isset($_SESSION['identifier']) && is_string($_SESSION['identifier'])) {
    $identifier = $_SESSION['identifier'];
}


try {
    $o = new OpenID_RelyingParty($returnTo, $realm, $identifier);
} catch (Throwable $e) {
    Die_error($e);
}


if (isset($_POST['start'])) {
    try {
        $authRequest = $o->prepare();
    } catch (Throwable $e) {
        Die_error($e);
    }

    $url = $authRequest->getAuthorizeURL();

    header('Location: ' . $url);
    exit;
}


if (! count($_POST)) {
    [, $queryString] = explode('?', $_SERVER['REQUEST_URI']);
} else {
    $queryString = file_get_contents('php://input');
}


try {
    $message = new OpenID_Message($queryString, OpenID_Message::FORMAT_HTTP);
} catch (Throwable $e) {
    Die_error($e);
}

$id = $message->get('openid.claimed_id');

if (empty($id) || ! isset($AUTH_MAP[$id])) {
    Show_page('<p>User not allowed!</p>');
    exit;
}

$_SESSION['PMA_single_signon_user'] = $AUTH_MAP[$id]['user'];
$_SESSION['PMA_single_signon_password'] = $AUTH_MAP[$id]['password'];
$_SESSION['PMA_single_signon_HMAC_secret'] = hash('sha1', uniqid(strval(random_int(0, mt_getrandmax())), true));
session_write_close();

header('Location: ../index.php');
