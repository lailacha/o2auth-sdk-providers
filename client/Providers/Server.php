<?php 

namespace App\Providers;

use App\AbstractAuthProvider;

require_once("AbstractAuthPovider.php");


class Server extends AbstractAuthProvider
{

    public function __construct(string $client_id, string $client_secret, string $redirect_uri, string $scope, array $params)
    {
        parent::__construct($client_id, $client_secret, $redirect_uri, $scope, $params);
    }
   
    public function getRequestTokenUri()
    {
        return "http://host.docker.internal:8080/token";
    }

    public function getAuthorizeUri()
    {
        return "http://localhost:8080/auth";
    }

    public function getBaseUri()
    {
        return "http://host.docker.internal:8080/me";
    }

    public function getUser(): array {

        $data = $this->fetchUserData();

        $user = [
            "first_name" => $data["first_name"] ?? "",
            "last_name" => $data["last_name"] ?? "",
            "provider_id" => $data["id"] ?? "",
        ];

       return $user; 
    }

}


?>