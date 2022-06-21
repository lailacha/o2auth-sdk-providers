<?



namespace App;

use App\Exceptions\NoAuhorizationCodeException;

 abstract class AbstractAuthProvider {
    protected string $redirect_uri;
    protected string $client_id;
    private string $client_secret;
    private string $scope;
    private string $method = 'GET';
    private string $grant_type = 'authorization_code';
    private string $access_token;


    abstract public function getBaseUri();

    abstract public function getRequestTokenUri();

    abstract public function getAuthorizeUri();


    public function __construct(string $client_id, string $client_secret, string $redirect_uri, string $scope)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        $this->scope = $scope;
    }


    public function callback()
    {
        if($_GET["code"])
        {
            $data =  http_build_query([
                "redirect_uri" => $this->redirect_uri,
                "client_id" => $this->client_id,
                "client_secret" => $this->client_secret,
                "grant_type" => $this->grant_type,
                "code" => $_GET["code"]
            ]);

            $url = "{$this->getRequestTokenUri()}?{$data}";

            $result = json_decode(file_get_contents($url), true);

            $this->accessToken = $result['access_token'];

    
            $url = "{$this->getBaseUri()}/me";
            $options = array(
                'http' => array(
                    'method' => $this->method,
                    'header' => 'Authorization: Bearer ' . $this->accessToken
                )
            );
        }
        else
        {
            throw new NoAuhorizationCodeException("Invalid authorization code");
        }

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result, true);
        echo "Hello {$result['name']}";
    
    }

    public function loginUrl() {

        $queryParams= http_build_query(array(
            "client_id" => $this->client_id,
            "redirect_uri" => $this->redirect_uri,
            "response_type" => "code",
            "scope" => $this->scope,
            "state" => bin2hex(random_bytes(16))
        ));

        return  "{$this->getAuthorizeUri()}?{$queryParams}";
    }
    
}

