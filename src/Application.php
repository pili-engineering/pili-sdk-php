<?php
namespace Pili;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class Application 
{

    const VERSION       = '0.1.1';
    const USER_AGENT    = 'pili-php';
    const API_END_POINT = 'http://api.pili.qiniu.com/v1/';

    private $accessKey;
    private $secretKey;

    public function __construct($accessKey, $secretKey)
    {
        $this->accessKey = $accessKey;
        $this->secretkey = $secretKey;
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

    private function getClient()
    {
        $client = new Client([
            'base_url' => self::API_END_POINT,
            'defaults' => [
                'headers' => [
                    'User-Agent'   => self::USER_AGENT .'/'. self::VERSION . ' ' . Client::getDefaultUserAgent(),
                    'Content-Type' => 'application/json',
                ],
            ]
        ]);
        $client->getEmitter()->attach(new Auth($this->accessKey, $this->secretkey));
        return $client;
    }

    public function createStream(array $params = [])
    {
        try {
            $data = empty($params) ? array('body' => '{}') : array('json' => $params);
            return $this->getClient()->post('streams', $data)->json();
        } catch (BadResponseException $e) {
            return $e->getResponse()->json();
        }
    }

    public function listStreams()
    {
        try {
            return $this->getClient()->get('streams')->json();
        } catch (BadResponseException $e) {
            return $e->getResponse()->json();
        }
    }

    public function getStream($streamId)
    {
        try {
            return $this->getClient()->get("streams/$streamId")->json();
        } catch (BadResponseException $e) {
            return $e->getResponse()->json();
        }
    }

    public function setStream($streamId, array $params = [])
    {
        try {
            $data = empty($params) ? array('body' => '{}') : array('json' => $params);
            return $this->getClient()->post("streams/$streamId", $data)->json();
        } catch (BadResponseException $e) {
            return $e->getResponse()->json();
        }

    }

    public function delStream($streamId)
    {
        try {
            return $this->getClient()->delete("streams/$streamId")->json();
        } catch (BadResponseException $e) {
            return $e->getResponse()->json();
        }
    }

    public function getStreamStatus($streamId)
    {
        try {
            return $this->getClient()->get("streams/$streamId/status")->json();
        } catch (BadResponseException $e) {
            return $e->getResponse()->json();
        }
    }

    public function getStreamSegments($streamId, $startTime, $endTime)
    {
        try {
            return $this->getClient()->get("streams/$streamId/segments", ['query' => ['starttime' => $startTime, 'endtime' => $endTime]])->json();
        } catch (BadResponseException $e) {
            return $e->getResponse()->json();
        }
    }

    public function playStreamSegments($streamId, $startTime, $endTime)
    {
        try {
            return $this->getClient()->get("streams/$streamId/segments/play", ['query' => ['starttime' => $startTime, 'endtime' => $endTime]])->json();
        } catch (BadResponseException $e) {
            return $e->getResponse()->json();
        }
    }

    public function delStreamSegments($streamId, $startTime, $endTime)
    {
        try {
            return $this->getClient()->delete("streams/$streamId/segments", ['query' => ['starttime' => $startTime, 'endtime' => $endTime]])->json();
        } catch (BadResponseException $e) {
            return $e->getResponse()->json();
        }
    }

}
