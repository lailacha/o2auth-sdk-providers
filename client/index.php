<?php

namespace App;

use App\Providers\Facebook;
use App\Providers\Twitter;
use App\Providers\Server;
use App\Providers\Discord;
use App\Providers\Twitch;



function myAutoloader( $class )
{
    $class = str_ireplace("App\\","",$class);
    $class = str_replace("\\","/",$class);
    
    if(file_exists($class.".php")){
        include $class.".php";
    }
}

spl_autoload_register("App\myAutoloader");

session_start();

$discord = new Discord("997451119636787221","WqvhvHLWT_TjSVNMVEijeqGD5CN5Mzrb","http://localhost:8081/discordAuth","identify guilds",[]);
$fb = new Facebook("440227080924149", "29a8bccc0ca25ddb6d71bfac4692d9ba", "http://localhost:8081/fbAUth", "public_profile,email", ["fields" => "name, email"]);
$server = new Server("62c0a5028df3c", "62c0a5028df45","http://localhost:8081/serverAuth", "t", [] );
$twitch = new Twitch("xbsvdclkcukplhqkwbd2jztapzha1t", "d70fzo7fxa4jlt1vtxj50tekie58db","http://localhost:8081/twitchAuth", "user_read", [] );


$route = $_SERVER['REQUEST_URI'];
switch (strtok($route, "?")) {
    case '/Fblogin':
        echo "<a href='{$fb->loginUrl()}'>Login with Facebook</a>";
        break;
    case '/serverLogin':
    echo "<a href='{$server->loginUrl()}'>Login with Server</a>";
    break;    
    case '/fbAUth':
        $fb->getToken();
        $data = $fb->getData();
              break;
    case '/serverAuth':
        echo $server->getToken();
        $data = $server->getData();
        echo var_dump($data);
        break;
   case '/discordAuth':
        echo $discord->getToken();
        $data = $discord->getData();
        echo var_dump($data);   
        die();
        break;   
        
    case '/twitchAuth':
        $twitch->getToken();
        $data = $twitch->getData();
        break;
    
    case '/user':
        $data = $fb->getUser();
        echo var_dump($data);
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>

body {
    background-color: black;

}
.container{
    display: flex;
    width: 100%;
    height: 100vh;
    align-items: center;
    justify-content: center;
    background-color: black;
}

.login {
    height: 50%;
    width: 25%;
    background-color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border-radius: 20px;
}

a {
    display: block;
    padding: 1em;
    border-radius: 20px;
    width: 50%;
}

a.facebook{
    background-color: #0b32a5;
    color: white;
}

a.server{
    background-color: #4d7762;
    color: white;
}

a.twitch {
    background-color: #8e07c4;
    color: white;
}

a.instagram {
    background-color: #e228ab;
    color: white;
}

a:not(:first-child){
    margin-top: 20px;
}

.result{
    background-color: white;
}
</style>
<body>
<div class="result">
<?php echo var_dump($data); ?>
</div>
    

<div class="container">
    <div class="login">
        <h2>O2auth Login</h2>
    <a class="facebook" href='<?php echo $fb->loginUrl() ?>'>Login with Facebook <svg width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M2.99609 20V11.0547H0V7.5H2.99609V4.69922C2.99609 1.65625 4.85547 0 7.57031 0C8.87109 0 9.98828 0.0976562 10.3125 0.140625V3.32031H8.42969C6.95312 3.32031 6.66797 4.02344 6.66797 5.05078V7.5H10L9.54297 11.0547H6.66797V20" fill="white"/>
</svg>
</a>
    <a class="instagram" href='<?php echo $twitch->loginUrl() ?>'>Login with Twith</a>
    <a class="server" href='<?php echo $server->loginUrl() ?>'>Login with LocalServer</a>
    <a class="twitch" href='<?php echo $discord->loginUrl() ?>'>Login with Discord</a>
    </div>
</div>

</body>
</html>