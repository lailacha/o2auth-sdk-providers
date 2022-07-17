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
    private array $options = [];
    private string $access_token;


    abstract public function getBaseUri();

    abstract public function getRequestTokenUri();

    abstract public function getAuthorizeUri();

    abstract public function getUser();


    public function __construct(string $client_id, string $client_secret, string $redirect_uri, ?string $scope, ?array $options = [])
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        $this->scope = $scope;
        $this->options = $options;
    }


    public function getToken()
    {
       // $result = (file_get_contents("http://host.docker.internal:8080/token?redirect_uri=http%3A%2F%2Flocalhost%3A8081%2FserverAuth&client_id=62c00b59b3dfd&client_secret=62c00b59b3e0d&grant_type=authorization_code&code=1233dd712a790ea504ae00070afdf717"));

       if($_GET["code"])
        {
            
            $requestData =  http_build_query([
                "redirect_uri" => $this->redirect_uri,
                "client_id" => $this->client_id,
                "client_secret" => $this->client_secret,
                "grant_type" => $this->grant_type,
                "code" => $_GET["code"]
            ]);

            $url = "{$this->getRequestTokenUri()}?{$requestData}";

            //Post with curl request
            $ch = curl_init();


            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
            $result = (curl_exec($ch));
            $result = json_decode($result, true);
                            
            $_SESSION['access_token'] = $result['access_token'];    
        }
        else
        {
            throw new \InvalidArgumentException("Invalid authorization code");
        }

    
    }


    public function getData() {
        
        $params = http_build_query($this->options);

        $url = "{$this->getBaseUri()}?{$params}";

        $options = array(
            'http' => array(
                'method' => "POST",
                'header' => 'Authorization: Bearer ' . $_SESSION['access_token']
            )
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL =>  $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Client-ID: ' . $this->client_id,
            'Authorization: Bearer ' . $_SESSION['access_token']),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);

    }


    public function fetchUserData()
    {

        $params = $this->options ? http_build_query($this->options) : "";

        $url = "{$this->getBaseUri()}?{$params}";

        $options = array(
            'http' => array(
                'method' => "POST",
                'header' => 'Authorization: Bearer ' . $_SESSION['access_token']
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
     
        return json_decode($result, true);

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

