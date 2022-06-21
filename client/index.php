<?php

namespace App;

use App\Providers\Facebook;

require_once("AbstractAuthPovider.php");
require_once("Providers/Facebook.php");
$fb = new Facebook("440227080924149", "29a8bccc0ca25ddb6d71bfac4692d9ba", "http://localhost:8081/callback", "public_profile,email");

$route = $_SERVER['REQUEST_URI'];
switch (strtok($route, "?")) {
    case '/Fblogin':
        echo "<a href='{$fb->loginUrl()}'>Login with Facebook</a>";
        break;
    case '/callback':
        $fb->callback();
        break;
    // case '/fb_callback':
    //     fbcallback();
    //     break;
    // default:
    //     echo '404';
    //     break;
}