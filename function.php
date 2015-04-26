<?php
define('FACEBOOK_SDK_V4_SRC_DIR', dirname(__FILE__) . '/facebook-php-sdk-v4-4.0-dev/src/Facebook/');
include(dirname(__FILE__) . '/lib/facebook-php-sdk-v4-4.0-dev/autoload.php');

define('FB_APP_ID',     'YOUR_APP_ID');
define('FB_APP_SECRET', 'YOUR_APP_SECRET');

define('IS_CLI', is_cli());
if (!IS_CLI)
    session_start(); // for facebook

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
FacebookSession::setDefaultApplication(FB_APP_ID, FB_APP_SECRET);

// {{{ function is_cli()
function is_cli()
{
    return (php_sapi_name() === 'cli') ? true : false;
}
// }}}
// {{{ function gen_facebook_login_url($from = '')
function gen_facebook_login_url($from = '')
{
    $redirect_uri = 'http://' . YOUR_HOSTNAME . '/login.php?from=' . urlencode($from);
    $fbrdhelper   = new \Facebook\FacebookRedirectLoginHelper($redirect_uri);
    $fb_login_url = $fbrdhelper->getLoginUrl(array('email'));

    return $fb_login_url;
}
// }}}
?>
