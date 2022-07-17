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

        $data = $this->getData();

        $user = [
            "first_name" => $data["firstname"] ?? "",
            "last_name" => $data["lastname"] ?? "",
            "provider_id" => $data["user_id"] ?? "",
        ];

       return $user; 
    }

}


?>