<?php

namespace App;

use App\ProviderFactory;



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

$config = [
    "discord" => [
    "client_id" => "997451119636787221",
    "client_secret" => "WqvhvHLWT_TjSVNMVEijeqGD5CN5Mzrb",
    "redirect_uri" => "http://localhost:8081/discordAuth",
    "scope" => "identify guilds email",
     "params" => []   
],
    "twitch" => [
        "client_id" => "xbsvdclkcukplhqkwbd2jztapzha1t",
        "client_secret" => "d70fzo7fxa4jlt1vtxj50tekie58db",
        "redirect_uri" => "http://localhost:8081/twitchAuth",
        "scope" => "user_read",
        "params" => []   
    ],
    "server" => [
        "client_id" => "62c0a5028df3c",
        "client_secret" => "62c0a5028df45",
        "redirect_uri" => "http://localhost:8081/serverAuth",
        "scope" => "t",
        "params" => []  
    ],
    "facebook" => [
        "client_id" => "440227080924149",
        "client_secret" => "29a8bccc0ca25ddb6d71bfac4692d9ba",
        "redirect_uri" => "http://localhost:8081/fbAUth",
        "scope" => "public_profile,email",
        "params" => ["fields" => "name, email, first_name, last_name"]
    ]
    ];

    $factory = new ProviderFactory($config);
    $fb = $factory->getProvider("facebook");
    $twitch = $factory->getProvider("twitch");
    $server = $factory->getProvider("server");
    $discord = $factory->getProvider("discord");


$route = $_SERVER['REQUEST_URI'];
switch (strtok($route, "?")) {
    case '/fbAUth':
        $fb->getToken();
        $data = $fb->getUser();
        break;
    case '/serverAuth':
        $server->getToken();
        $data = $server->getUser();
        break;
   case '/discordAuth':
        $discord->getToken();
        $data = $discord->getUser();
        break;   
        
    case '/twitchAuth':
        $twitch->getToken();
        $data = $twitch->getUser();
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O2Auth</title>
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
    display: flex;
    padding: 1em;
    border-radius: 20px;
    width: 50%;
    text-decoration: none;
}


svg{
    margin-left: auto;
}

a.facebook{
    background-color: #0b32a5;
    color: white;
}

a.server{
    background-color: #4d7762;
    color: white;
}

a.discord {
    background-color: #5a58e8;
    color: white;
}

a.twitch {
    background-color: #650ed6;
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
    User:
<?php echo isset($data) ?  var_dump($data) : ""?>
</div>
    

<div class="container">
    <div class="login">
        <h2>O2auth Login</h2>
    <a class="facebook" href='<?php echo $fb->loginUrl() ?>'>Login with Facebook <svg width="11" height="20" viewBox="0 0 11 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M2.99609 20V11.0547H0V7.5H2.99609V4.69922C2.99609 1.65625 4.85547 0 7.57031 0C8.87109 0 9.98828 0.0976562 10.3125 0.140625V3.32031H8.42969C6.95312 3.32031 6.66797 4.02344 6.66797 5.05078V7.5H10L9.54297 11.0547H6.66797V20" fill="white"/>
</svg>
</a>
    <a class="twitch" href='<?php echo $twitch->loginUrl() ?>'>Login with Twitch</a>
    <a class="server" href='<?php echo $server->loginUrl() ?>'>Login with LocalServer</a>
    <a class="discord" href='<?php echo $discord->loginUrl() ?>'>Login with Discord</a>
    </div>
</div>

</body>
</html>