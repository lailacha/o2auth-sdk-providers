<?php 

namespace App\Providers;

use App\AbstractAuthProvider;

require_once("AbstractAuthPovider.php");


class Twitter extends AbstractAuthProvider
{

    public function __construct(string $client_id, string $client_secret, string $redirect_uri, string $scope, array $params)
    {
        parent::__construct($client_id, $client_secret, $redirect_uri, $scope, $params);
    }
   
    public function getRequestTokenUri()
    {
        return "https://api.twitter.com/oauth/request_token";
    }

    public function getAuthorizeUri()
    {
        return "";
        //twitter auth url to get a code from twitter
        //return "https://api.twitter.com/oauth/authorize";
    }

    public function getBaseUri()
    {
        return "https://graph.facebook.com/v2.10";
    }


    public function getUser(): array {

        $data = $this->fetchUserData();

        $user = [
            "first_name" => $data["first_name"] ?? "",
            "last_name" => $data["last_name"] ?? "",
            "email" => $data["email"] ?? "",
            "provider_id" => $data["id"] ?? "",
        ];

       return $user; 
    }

}


?>