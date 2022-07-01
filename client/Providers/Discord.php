<?php


define('OAUTH2_CLIENT_ID', '989268751880966196');
define('OAUTH2_CLIENT_SECRET', '27dvINuER04lAsxswqcpJod_86vO8VRV');

$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$apiURLBase = 'https://discord.com/api/users/@me';

session_start();


if(get('action') == 'login') {

  $params = array(
    'client_id' => "989268751880966196",
    'redirect_uri' => 'http://localhost:8081/dis_callback',
    'response_type' => 'code',
    'scope' => 'identify guilds'
  );

  
  header('Location: https://discordapp.com/api/oauth2/authorize' . '?' . http_build_query($params));
  die();
}
echo get('code');


if(get('code')) {
    
  
  $token = apiRequest($tokenURL, array(
    "grant_type" => "authorization_code",
    'client_id' => "989268751880966196",
    'client_secret' => "27dvINuER04lAsxswqcpJod_86vO8VRV",
    'redirect_uri' => 'http://localhost:8081/dis_callback',
    'code' => get('code')
  ));
  $logout_token = $token->access_token;
  $_SESSION['access_token'] = $token->access_token;


  header('Location: ' . $_SERVER['PHP_SELF']);
}

if(session('access_token')) {
  $user = apiRequest($apiURLBase);

  
  echo '<h4>Salut, ' . $user->username . '</h4>';


} else {
  echo '<h3>Not logged in</h3>';
  echo '<p><a href="?action=login">Log In</a></p>';
}


if(get('action') == 'logout') {
  

  $params = array(
    'access_token' => $logout_token
  );


  header('Location: https://discordapp.com/api/oauth2/token/revoke' . '?' . http_build_query($params));
  die();
}

function apiRequest($url, $post=FALSE, $headers=array()) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

  $response = curl_exec($ch);


  if($post)
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

  $headers[] = 'Accept: application/json';

  if(session('access_token'))
    $headers[] = 'Authorization: Bearer ' . session('access_token');

  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $response = curl_exec($ch);
  return json_decode($response);
}

function get($key, $default=NULL) {
  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default=NULL) {
  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}

?>