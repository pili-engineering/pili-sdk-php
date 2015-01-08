<?php

use PiliIO\Utils;
use PiliIO\HttpRequest;

class PiliIO
{

    const VERSION      = '0.1.2';
    const API_BASE_URL = 'http://api.pili.qiniu.com/v1/';

    private $accessKey;
    private $secretKey;

    public function __construct($accessKey, $secretKey)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    public function signPushUrl($url, $streamKey, $nonce = 0)
    {
        if(!$nonce) {
            $nonce = time();
        }
        $url .= '?nonce=' . $nonce;
        $url .= '&token=' . Utils::sign($streamKey, $url);
        return $url;
    }

    public function signPlayUrl($url, $streamKey, $expiry)
    {
        $url .= '?expiry=' . $expiry;
        $url .= '&token=' . Utils::sign($streamKey, $url);
        return $url;
    }

    public function createStream($params = array())
    {
        $url = self::API_BASE_URL . 'streams';
        $body = empty($params) ? '{}' : json_encode($params);
        return $this->request(HttpRequest::POST, $url, $body);
    }

    public function listStreams()
    {
        $url = self::API_BASE_URL . 'streams';
        return $this->request(HttpRequest::GET, $url);
    }

    public function getStream($streamId)
    {
        $url  = self::API_BASE_URL . "streams/$streamId";
        return $this->request(HttpRequest::GET, $url);
    }

    public function setStream($streamId, $params = array())
    {
        $url  = self::API_BASE_URL . "streams/$streamId";
        $body = empty($params) ? '{}' : json_encode($params);
        return $this->request(HttpRequest::POST, $url, $body);
    }

    public function delStream($streamId)
    {
        $url  = self::API_BASE_URL . "streams/$streamId";
        return $this->request(HttpRequest::DELETE, $url);
    }

    public function getStreamStatus($streamId)
    {
        $url  = self::API_BASE_URL . "streams/$streamId/status";
        return $this->request(HttpRequest::GET, $url);
    }

    public function getStreamSegments($streamId, $startTime, $endTime)
    {
        $url  = self::API_BASE_URL . "streams/$streamId/segments?starttime=$startTime&endtime=$endTime";
        return $this->request(HttpRequest::GET, $url);
    }

    public function playStreamSegments($streamId, $startTime, $endTime)
    {
        $url  = self::API_BASE_URL . "streams/$streamId/segments/play?starttime=$startTime&endtime=$endTime";
        return $this->request(HttpRequest::GET, $url);
    }

    public function delStreamSegments($streamId, $startTime, $endTime)
    {
        $url  = self::API_BASE_URL . "streams/$streamId/segments?starttime=$startTime&endtime=$endTime";
        return $this->request(HttpRequest::DELETE, $url);
    }

    private function setHeaders($url, $body = NULL)
    {
        return array(
            'Content-Type'  => 'application/json',
            'user-agent'    => Utils::getUserAgent(self::VERSION),
            'Authorization' => Utils::signRequest($this->accessKey, $this->secretKey, $url, $body),
        );
    }

    private function request($method, $url, $body = NULL)
    {
        $headers = $this->setHeaders($url, $body);
        $response = HttpRequest::send($method, $url, $body, $headers);
        return $response->body;
    }
}

?>
