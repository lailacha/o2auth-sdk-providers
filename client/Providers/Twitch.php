<?php 

namespace App\Providers;

use App\AbstractAuthProvider;

require_once("AbstractAuthPovider.php");


class Twitch extends AbstractAuthProvider
{

    public function __construct(string $client_id, string $client_secret, string $redirect_uri, string $scope, array $params)
    {
        parent::__construct($client_id, $client_secret, $redirect_uri, $scope, $params);
    }
   
    public function getRequestTokenUri()
    {
        return "https://api.twitch.tv/kraken/oauth2/token";
    }

    public function getAuthorizeUri()
    {
        return "https://api.twitch.tv/kraken/oauth2/authorize";
    }

    public function getBaseUri()
    {
        return "https://api.twitch.tv/helix/users";
    }


    public function getUser(): array {

        $data = $this->getData();

        $user = [
            "user_name" => $data["login"] ?? "",
            "first_name" => $data["first_name"] ?? "",
            "last_name" => $data["last_name"] ?? "",
            "email" => $data["email"] ?? "",
            "provider_id" => $data["id"] ?? "",
            "provider_name" => "twitch",
        ];

       return $user; 
    }


}


?>