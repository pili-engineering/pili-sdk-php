<?php
namespace Pili;

use Pili\Utils;
use Pili\HttpRequest;

class Auth
{
    private $_accessKey;
    private $_secretKey;

    public function __construct($accessKey, $secretKey)
    {
        $this->_accessKey = $accessKey;
        $this->_secretKey = $secretKey;
    }

    private function _setHeaders($method, $url, $body = NULL)
    {
        $headers = array(
            'Content-Type'  => 'application/json',
            'User-Agent'    => Utils::getUserAgent(),
        );
        $authorization = Utils::signRequest($this->_accessKey, $this->_secretKey, $method, $url, $headers['Content-Type'], $body);
        $headers = array_merge($headers, array('Authorization' => $authorization));
        return $headers;
    }

    public function request($method, $url, $body = NULL)
    {
        $headers = $this->_setHeaders($method, $url, $body);
        $response = HttpRequest::send($method, $url, $body, $headers);
        return $response->body;
    }
}
?>