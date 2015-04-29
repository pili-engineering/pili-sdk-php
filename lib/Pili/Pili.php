<?php

use Pili\Utils;
use Pili\HttpRequest;

class Pili
{

    const VERSION                   = '1.0.1';
    const API_BASE_URL              = 'http://pili.qiniuapi.com/v1/';
    const CONTENT_TYPE              = 'application/json';
    const DEFAULT_RTMP_PUBLISH_HOST = 'pub.z1.glb.pili.qiniup.com';

    private $_accessKey;
    private $_secretKey;

    public function __construct($accessKey, $secretKey)
    {
        $this->_accessKey = $accessKey;
        $this->_secretKey = $secretKey;
    }

    public function createStream($hubName, $title = NULL, $publishKey = NULL, $publishSecurity = NULL)
    {
        $url = self::API_BASE_URL . 'streams';
        $params = array('hub' => $hubName);
        if (!empty($title)) {
            $params = array_merge($params, array('title' => $title));
        }
        if (!empty($publishKey)) {
            $params = array_merge($params, array('publishKey' => $publishKey));
        }
        if (!empty($publishSecurity)) {
            $params = array_merge($params, array('publishSecurity' => $publishSecurity));
        }
        $body = json_encode($params);
        return $this->_request(HttpRequest::POST, $url, $body);
    }

    public function getStream($streamId)
    {
        $url  = self::API_BASE_URL . "streams/$streamId";
        return $this->_request(HttpRequest::GET, $url);
    }

    public function setStream($streamId, $publishKey = NULL, $publishSecurity = NULL)
    {
        $url  = self::API_BASE_URL . "streams/$streamId";
        $params = array();
        if (!empty($publishKey)) {
            $params = array_merge($params, array('publishKey' => $publishKey));
        }
        if (!empty($publishSecurity)) {
            $params = array_merge($params, array('publishSecurity' => $publishSecurity));
        }
        $body = json_encode($params);
        $body = empty($body) ? '{}' : $body;
        return $this->_request(HttpRequest::POST, $url, $body);
    }

    public function listStreams($hubName, $marker = NULL, $limit = NULL)
    {
        $url = self::API_BASE_URL . "streams?hub=$hubName";
        if (!empty($marker)) {
            $url .= "&marker=$marker";
        }
        if (!empty($limit)) {
            $url .= "&limit=$limit";
        }
        return $this->_request(HttpRequest::GET, $url);
    }

    public function getStreamSegments($streamId, $startTime = NULL, $endTime = NULL)
    {
        $url  = self::API_BASE_URL . "streams/$streamId/segments";
        if (!empty($startTime)) {
            $url .= "?start=$startTime";
        }
        if (!empty($endTime)) {
            $url .= "&end=$endTime";
        }
        return $this->_request(HttpRequest::GET, $url);
    }

    public function delStream($streamId)
    {
        $url  = self::API_BASE_URL . "streams/$streamId";
        return $this->_request(HttpRequest::DELETE, $url);
    }

    private function _setHeaders($method, $url, $body = NULL)
    {
        $userAgent     = Utils::getUserAgent(self::VERSION);
        $authorization = Utils::signRequest($this->_accessKey, $this->_secretKey, $method, $url, self::CONTENT_TYPE, $body);
        $headers = array(
            'User-Agent'    => $userAgent,
            'Authorization' => $authorization,
        );
        if (!empty($body)) {
            $headers = array_merge($headers, array('Content-Type' => self::CONTENT_TYPE));
        }
        return $headers;
    }

    private function _request($method, $url, $body = NULL)
    {
        $headers = $this->_setHeaders($method, $url, $body);
        $response = HttpRequest::send($method, $url, $body, $headers);
        return $response->body;
    }



    public function publishUrl($streamId, $publishKey, $publishSecurity = NULL, $nonce = NULL, $scheme = 'rtmp')
    {
        switch ($publishSecurity) {
            case 'dynamic':
                $url = $this->_buildDynamicUrl($streamId, $publishKey, $nonce, $scheme);
                break;
            case 'static':
                $url = $this->_buildStaticUrl($streamId, $PublishKey, $scheme);
                break;
            default:
                $url = $this->_buildDynamicUrl($streamId, $publishKey, $nonce, $scheme);
                break;
        }
        return $url;
    }

    private function _resolvePath($streamId)
    {
        $pieces = explode('.', $streamId);
        $baseUri = "/$pieces[1]/$pieces[2]";
        return $baseUri;
    }

    private function _buildStaticUrl($streamId, $PublishKey, $scheme)
    {
        return $scheme . '://' . self::DEFAULT_RTMP_PUBLISH_HOST . $this->_resolvePath($streamId) . '?key=' . $PublishKey;
    }

    private function _buildDynamicUrl($streamId, $publishKey, $nonce, $scheme)
    {
        if(empty($nonce)) {
            $nonce = time();
        }
        $baseUrl = $scheme . '://' . self::DEFAULT_RTMP_PUBLISH_HOST . $this->_resolvePath($streamId) . '?nonce=' . $nonce;
        $publishToken = Utils::sign($publishKey, $baseUrl);
        return $baseUrl . '&token=' . $publishToken;
    }



    public function rtmpLiveUrl($rtmpPlayHost, $streamId, $preset = NULL, $scheme = 'rtmp')
    {
        $baseUri = $this->_resolvePath($streamId);
        $url = $scheme . '://' . $rtmpPlayHost . $baseUri;
        if (!empty($preset)) {
            $url .= '@' . $preset;
        }
        return $url;
    }

    public function hlsLiveUrl($hlsPlayHost, $streamId, $preset = NULL, $scheme = 'http')
    {
        $baseUri = $this->_resolvePath($streamId);
        $url = $scheme . '://' . $hlsPlayHost . $baseUri;
        if (!empty($preset)) {
            $url .= '@' . $preset;
        }
        $url .= '.m3u8';
        return $url;
    }

    public function hlsPlaybackUrl($hlsPlayHost, $streamId, $startTime, $endTime, $preset = NULL, $scheme = 'http')
    {
        $baseUri = $this->_resolvePath($streamId);
        $url = $scheme . '://' . $hlsPlayHost . $baseUri;
        if (!empty($preset)) {
            $url .= '@' . $preset;
        }
        $url .= '.m3u8';
        if (!empty($startTime)) {
            $url .= "?start=$startTime";
        }
        if (!empty($endTime)) {
            $url .= "&end=$endTime";
        }
        return $url;
    }

}

?>
