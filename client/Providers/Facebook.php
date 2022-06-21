<?php 

namespace App\Providers;

use App\AbstractAuthProvider;

require_once("AbstractAuthPovider.php");


class Facebook extends AbstractAuthProvider
{

    public function __construct(string $client_id, string $client_secret, string $redirect_uri, string $scope)
    {
        parent::__construct($client_id, $client_secret, $redirect_uri, $scope);
    }
   
    public function getRequestTokenUri()
    {
        return "https://graph.facebook.com/v2.10/oauth/access_token";
    }

    public function getAuthorizeUri()
    {
        return "https://www.facebook.com/v2.10/dialog/oauth";
    }

    public function getBaseUri()
    {
        return "https://graph.facebook.com/v2.10";
    }

}


?>