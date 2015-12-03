<?php
namespace Pili;

use \Pili\Transport;
use \Pili\Api;
use \Pili\Stream;

class Hub
{
    private $_hub;

    private $_transport;

    public function __construct($credentials, $hubName)
    {
        $this->_hub = $hubName;
        $this->_transport = new Transport($credentials);
    }

    public function createStream($title = NULL, $publishKey = NULL, $publishSecurity = NULL)
    {
        $stream = Api::createStream($this->_transport, $this->_hub, $title, $publishKey, $publishSecurity);
        return new Stream($this->_transport, $stream);
    }

    public function getStream($streamId)
    {
        $stream = Api::getStream($this->_transport, $streamId);
        return new Stream($this->_transport, $stream);
    }

    public function listStreams($marker = NULL, $limit = NULL, $title_prefix = NULL, $status = NULL)
    {
        $result = Api::listStreams($this->_transport, $this->_hub, $marker, $limit, $title_prefix, $status);
        $streams = $result["items"];
        foreach ($streams as &$stream) {
            $stream = new Stream($this->_transport, $stream);
        }
        $result["items"] = $streams;
        unset($stream);
        return $result;
    }
}
?>