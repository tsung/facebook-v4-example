<?php
include('function.php');

// Facebook need argument
$state = filter_input(INPUT_GET, 'state', FILTER_SANITIZE_SPECIAL_CHARS);
$code  = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_SPECIAL_CHARS);

$from  = '/';
$redirect_uri = 'http://YOUR_DOMAIN/login.php?from=' . urlencode($from);
$fbrdhelper   = new \Facebook\FacebookRedirectLoginHelper($redirect_uri);

$fbsession = false;
// save fb token to generator fb session and api usage
if (isset($_SESSION['fbtoken']) && $_SESSION['fbtoken'] != '') { // already get token
    try {
        $fbsession = new \Facebook\FacebookSession($_SESSION['fbtoken']);

        if (!$fbsession->validate())
            $fbsession = null;

    } catch (Exception $e) {
        $fbsession = null;
    }
} else { // first login
    if (!empty($code) && !empty($state)) {
        try {
            $fbsession = $fbrdhelper->getSessionFromRedirect();
            var_dump($fbsession);
        } catch(Exception $ex) {
            echo "Exception:{$ex->getMessage()}:";
            $fbsession = null;
        }
    }
}

// use facebook api (by token), other page usage
if ($fbsession !== null) {
    $_SESSION['fbtoken'] = $fbsession->getToken();
    $fbsession = new \Facebook\FacebookSession($_SESSION['fbtoken']);  // work-around
    $req = (new \Facebook\FacebookRequest($fbsession, 'GET', '/me'))->execute();
    $profile = json_decode($req->getRawResponse());

    var_dump($profile);
}

// HTML layout
if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
    // generator facebook login url
    $fb_login_url = $fbrdhelper->getLoginUrl(array('email'));

    echo "<a href=\"$fb_login_url\">Facebook Login</a>";
} else {
    // Logout ..
    echo "<a href=\"/logout.php\">Facebook Logout</a>";
}
?>
