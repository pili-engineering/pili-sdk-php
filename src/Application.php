<?php
namespace Pili;

class Application 
{

    const VERSION       = '0.1.1';
    const USER_AGENT    = 'pili-php';
    const API_END_POINT = 'http://api.pili.qiniu.com/v1/';

    private $accessKey;
    private $secretkey;

    public function __construct($access_key, $secret_key)
    {
        $this->accessKey = $access_key;
        $this->secretkey = $secret_key;
    }

    public function signPushUrl($url, $stream_key, $nonce = 0)
    {
        if(!$nonce) {
            $nonce = time();
        }
        $url .= '?nonce=' . $nonce;
        $url .= '&token=' . Utils::sign($stream_key, $url);
        return $url;
    }

    public function signPlayUrl($url, $stream_key, $expiry)
    {
        $url .= '?expiry=' . $expiry;
        $url .= '&token=' . Utils::sign($stream_key, $url);
        return $url;
    }

    private function getClient()
    {
        $client = new \GuzzleHttp\Client([
            'base_url' => self::API_END_POINT,
            'defaults' => [
                'headers' => [
                    'User-Agent'   => self::USER_AGENT .'/'. self::VERSION . ' ' . \GuzzleHttp\Client::getDefaultUserAgent(),
                    'Content-Type' => 'application/json',
                ],
            ]
        ]);
        $client->getEmitter()->attach(new Auth($this->accessKey, $this->secretkey));
        return $client;
    }

    public function createStream(array $params = [])
    {
        $data = empty($params) ? array('body' => '{}') : array('json' => $params);
        return $this->getClient()->post('streams', $data)->json();
    }

    public function listStreams()
    {
        return $this->getClient()->get('streams')->json();
    }

    public function getStream($stream_id)
    {
        return $this->getClient()->get("streams/$stream_id")->json();
    }

    public function setStream($stream_id, array $params = [])
    {
        $data = empty($params) ? array('body' => '{}') : array('json' => $params);
        return $this->getClient()->post("streams/$stream_id", $data)->json();
    }

    public function delStream($stream_id)
    {
        return $this->getClient()->delete("streams/$stream_id")->json();
    }

    public function getStreamStatus($stream_id)
    {
        return $this->getClient()->get("streams/$stream_id/status")->json();
    }

    public function getStreamSegments($stream_id, $starttime, $endtime)
    {
        return $this->getClient()->get("streams/$stream_id/segments", ['query' => ['starttime' => $starttime, 'endtime' => $endtime]])->json();
    }

    public function playStreamSegments($stream_id, $starttime, $endtime)
    {
        return $this->getClient()->get("streams/$stream_id/segments/play", ['query' => ['starttime' => $starttime, 'endtime' => $endtime]])->json();
    }

    public function delStreamSegments($stream_id, $starttime, $endtime)
    {
        return $this->getClient()->delete("streams/$stream_id/segments", ['query' => ['starttime' => $starttime, 'endtime' => $endtime]])->json();
    }

}
