<?php


class Proxy
{

    /**
     * Url of SSO server
     * @var string
     */
    protected $url;

    /**
     * My identifier, given by SSO provider.
     * @var string
     */
    public $broker;

    /**
     * My secret word, given by SSO provider.
     * @var string
     */
    protected $secret;

    /**
     * Session token of the client
     * @var string
     */
    public $token;

    /**
     * User info recieved from the server.
     * @var array
     */
    protected $userinfo;

    /**
     * Cookie lifetime
     * @var int
     */
    protected $cookie_lifetime;

    /**
     * Class constructor
     *
     * @param string $url Url of SSO server
     * @param string $broker My identifier, given by SSO provider.
     * @param string $secret My secret word, given by SSO provider.
     */
    public function __construct($url, $broker, $secret, $cookie_lifetime = 3600)
    {
        if (!$url) throw new \InvalidArgumentException("SSO server URL not specified");
        if (!$broker) throw new \InvalidArgumentException("SSO broker id not specified");
        if (!$secret) throw new \InvalidArgumentException("SSO broker secret not specified");

        $this->url = $url;
        $this->broker = $broker;
        $this->secret = $secret;
        $this->cookie_lifetime = $cookie_lifetime;

        if (isset($_COOKIE[$this->getCookieName()])) $this->token = $_COOKIE[$this->getCookieName()];
    }

    /**
     * Get the cookie name.
     *
     * Note: Using the broker name in the cookie name.
     * This resolves issues when multiple brokers are on the same domain.
     *
     * @return string
     */
    protected function getCookieName()
    {
        return 'sso_token_' . preg_replace('/[_\W]+/', '_', strtolower($this->broker));
    }

    /**
     * Generate session id from session key
     *
     * @return string
     */
    protected function getSessionId()
    {
        if (!isset($this->token)) return null;

        $checksum = hash('sha256', 'session' . $this->token . $this->secret);
        return "SSO-{$this->broker}-{$this->token}-$checksum";
    }

    /**
     * Generate session token
     */
    public function generateToken()
    {
        if (isset($this->token)) return;

        $this->token = base_convert(md5(uniqid(rand(), true)), 16, 36);
        setcookie($this->getCookieName(), $this->token, time() + $this->cookie_lifetime, '/');
    }

    /**
     * Clears session token
     */
    public function clearToken()
    {
        setcookie($this->getCookieName(), null, 1, '/');
        $this->token = null;
    }

    /**
     * Check if we have an SSO token.
     *
     * @return boolean
     */
    public function isAttached()
    {
        return isset($this->token);
    }

    /**
     * Get URL to attach session at SSO server.
     *
     * @param array $params
     * @return string
     */
    public function getAttachUrl($params = [])
    {
        $this->generateToken();

        $data = [
                'command' => 'attach',
                'broker' => $this->broker,
                'token' => $this->token,
                'checksum' => hash('sha256', 'attach' . $this->token . $this->secret)
            ] + $_GET;

        return $this->url . "?" . http_build_query($data + $params);
    }

    /**
     * Attach our session to the user's session on the SSO server.
     *
     * @param string|true $returnUrl The URL the client should be returned to after attaching
     */
    public function attach($returnUrl = null)
    {
        if ($this->isAttached()) return;

        if ($returnUrl === true) {
            $protocol = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
            $returnUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        $params = ['return_url' => $returnUrl];
        $url = $this->getAttachUrl($params);

        header("Location: $url", true, 307);
        echo "You're redirected to <a href='$url'>$url</a>";
        exit();
    }

    /**
     * Get the request url for a command
     *
     * @param string $command
     * @param array $params Query parameters
     * @return string
     */
    protected function getRequestUrl($command, $params = [])
    {
        $params['command'] = $command;
        return $this->url . '/index.php?' . http_build_query($params);
    }

    /**
     * Execute on SSO server.
     *
     * @param string $method HTTP method: 'GET', 'POST', 'DELETE'
     * @param string $command Command
     * @param array|string $data Query or post parameters
     * @return array|object
     */
    protected function request($method, $command, $data = null)
    {
        if (!$this->isAttached()) {
            throw new NotAttachedException('No token');
        }
        $url = $this->getRequestUrl($command, !$data || $method === 'POST' ? [] : $data);
        file_put_contents("./haha.txt",$url,FILE_APPEND);
        $headers = ['Accept: application/json', 'Authorization: Bearer '.$this->getSessionID()];

        $res=$this->curlRequest($url, $method, $data, $headers);

        return $res;
    }

    public function curlRequest($url, $method, $params, $headers, $url_encoded_format = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($method == 'GET') {
            if (!empty($params))
                $url = $url . '?' . http_build_query($params);
        } else {
            curl_setopt($ch, CURLOPT_POST, 1);
            if ($url_encoded_format) {
                $params = http_build_query($params);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        $response=str_ireplace('NULL','',$response);
        file_put_contents("./haha.txt",$response,FILE_APPEND);
        $error = curl_errno($ch);
        if ($error) {
            $message = 'Server request failed: ' . curl_error($ch);
            throw new Exception($message);
        }
        curl_close($ch);
        $data = json_decode($response, true);
        return $data;
    }


    /**
     * Log the client in at the SSO server.
     *
     * Only brokers marked trused can collect and send the user's credentials. Other brokers should omit $username and
     * $password.
     *
     * @param string $username
     * @param string $password
     * @return array  user info
     * @throws Exception if login fails eg due to incorrect credentials
     */
    public function login($username = null, $password = null)
    {
        if (!isset($username) && isset($_POST['username'])) $username = $_POST['username'];
        if (!isset($password) && isset($_POST['password'])) $password = $_POST['password'];

        $result = $this->request('POST', 'login', compact('username', 'password'));print_r($result);die('dd');
        $this->userinfo = $result;

        return $this->userinfo;
    }

    /**
     * Logout at sso server.
     */
    public function logout()
    {
        $this->request('POST', 'logout', 'logout');
    }

    /**
     * Get user information.
     *
     * @return object|null
     */
    public function getUserInfo()
    {
        if (!isset($this->userinfo)) {
            $this->userinfo = $this->request('GET', 'userInfo');
        }

        return $this->userinfo;
    }

    /**
     * Magic method to do arbitrary request
     *
     * @param string $fn
     * @param array $args
     * @return mixed
     */
    public function __call($fn, $args)
    {
        $sentence = strtolower(preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $fn));
        $parts = explode(' ', $sentence);

        $method = count($parts) > 1 && in_array(strtoupper($parts[0]), ['GET', 'DELETE'])
            ? strtoupper(array_shift($parts))
            : 'POST';
        $command = join('-', $parts);

        return $this->request($method, $command, $args);
    }
}