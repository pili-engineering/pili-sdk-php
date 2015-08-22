<?php

use Pili\Conf;
use Pili\Utils;
use Pili\HttpRequest;
use Pili\Auth;
use Pili\Api;
use Pili\Stream;

class Pili
{
    private $_auth;
    private $_hub;

    public function __construct($accessKey, $secretKey, $hubName)
    {
        $this->_auth = new Auth($accessKey, $secretKey);
        $this->_hub  = $hubName;
    }

    public function config($property, $value)
    {
        Conf::getInstance()->$property = $value;
    }

    public function createStream($title = NULL, $publishKey = NULL, $publishSecurity = NULL)
    {
        $stream = Api::createStream($this->_auth, $this->_hub, $title, $publishKey, $publishSecurity);
        return new Stream($this->_auth, $stream);
    }

    public function getStream($streamId)
    {
        $stream = Api::getStream($this->_auth, $streamId);
        return new Stream($this->_auth, $stream);
    }

    public function listStreams($marker = NULL, $limit = NULL, $title_prefix = NULL)
    {
        $result = Api::listStreams($this->_auth, $this->_hub, $marker, $limit, $title_prefix);
        $streams = $result["items"];
        foreach ($streams as &$stream) {
            $stream = new Stream($this->_auth, $stream);
        }
        $result["items"] = $streams;
        unset($stream);
        return $result;
    }
}
?>