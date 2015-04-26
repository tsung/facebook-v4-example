<?php
include('function.php');

// Facebook need argument
$state = filter_input(INPUT_GET, 'state', FILTER_SANITIZE_SPECIAL_CHARS);
$code  = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_SPECIAL_CHARS);

$from  = '/';
$redirect_uri = 'http://YOUR_DOMAIN/login.php?from=' . urlencode($from);
$fbrdhelper   = new \Facebook\FacebookRedirectLoginHelper($redirect_uri);

$fbsession = false;
if (!empty($code) && !empty($state)) {
    try {
        $fbsession = $fbrdhelper->getSessionFromRedirect();
    } catch(Exception $ex) {
        // echo "Exception:{$ex->getMessage()}:";
        $fbsession = false;
    }
}

if ($fbsession && $fbsession->validate()) {
    $req = (new \Facebook\FacebookRequest($fbsession, 'GET', '/me'))->execute();
    $profile = json_decode($req->getRawResponse());

    $uid = $profile->id; // facebook uniq id for this app
    echo $profile->name;
    echo $profile->email;

    $_SESSION['login'] = $uid;
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
