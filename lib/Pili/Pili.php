<?php

use Pili\Utils;

class Pili
{

    const VERSION      = '0.1.1';
    const API_BASE_URL = 'http://api.pili.qiniu.com/v1/';

    private $accessKey;
    private $secretKey;

    public function __construct($accessKey, $secretKey)
    {

        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    private function setHeaders($url, $body = NULL)
    {
        return array(
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
            'user-agent'    => Utils::getUserAgent(self::VERSION),
            'Authorization' => Utils::signRequest($this->accessKey, $this->secretKey, $url, $body),
        );
    }


    private function httpGet($url)
    {
        $headers = $this->setHeaders($url);
        $response = Unirest::get($url, $headers);
        return json_decode($response->raw_body, true);
    }

    private function httpPost($url, $body)
    {
        $headers = $this->setHeaders($url, $body);
        $response = Unirest::post($url, $headers, $body);
        return json_decode($response->raw_body, true);
    }

    private function httpDelete($url)
    {
        $headers = $this->setHeaders($url);
        $response = Unirest::delete($url, $headers, $body);
        return json_decode($response->raw_body, true);
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
        return $this->httpPost($url, $body);
    }

    public function listStreams()
    {
        $url = self::API_BASE_URL . 'streams';
        return $this->httpGet($url);
    }

    public function getStream($streamId)
    {
        $url  = self::API_BASE_URL . "streams/$streamId";
        return $this->httpGet($url);
    }

    public function setStream($streamId, $params = array())
    {
        $url  = self::API_BASE_URL . "streams/$streamId";
        $body = empty($params) ? '{}' : json_encode($params);
        return $this->httpPost($url, $body);
    }

    public function delStream($streamId)
    {
        $url  = self::API_BASE_URL . "streams/$streamId";
        return $this->httpDelete($url);
    }

    public function getStreamStatus($streamId)
    {
        $url  = self::API_BASE_URL . "streams/$streamId/status";
        return $this->httpGet($url);
    }

    public function getStreamSegments($streamId, $startTime, $endTime)
    {
        $url  = self::API_BASE_URL . "streams/$streamId/segments?starttime=$startTime&endtime=$endTime";
        return $this->httpGet($url);
    }

    public function playStreamSegments($streamId, $startTime, $endTime)
    {
        $url  = self::API_BASE_URL . "streams/$streamId/segments/play?starttime=$startTime&endtime=$endTime";
        return $this->httpGet($url);
    }

    public function delStreamSegments($streamId, $startTime, $endTime)
    {
        $url  = self::API_BASE_URL . "streams/$streamId/segments?starttime=$startTime&endtime=$endTime";
        return $this->httpDelete($url);
    }
}

?>
