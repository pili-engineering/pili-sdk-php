<?php
namespace Pili;

use \Pili\Transport;
use \Pili\Stream;
use \Qiniu\HttpRequest;
use \Qiniu\Utils;


class Hub
{
    private $_hub;
    private $_baseURL;
    private $_transport;

    public function __construct($credentials, $hubName)
    {
        $this->_hub = $hubName;
        $this->_transport = new Transport($credentials);

        $cfg = Config::getInstance();
        $protocal = $cfg->USE_HTTPS === true ? "https" : "http";
        $this->_baseURL = $protocal . "://" . $cfg->API_HOST . "/v2/hubs/" . $this->_hub;
    }

    public function create($streamKey){
        $url = $this->_baseURL . "/streams";
        $params['key'] = $streamKey;
        $body = json_encode($params);
        try {
            $this->_transport->send(HttpRequest::POST, $url, $body);
        }catch(\Exception $e){
            return $e;
        }

        return new \Pili\Stream($this->_transport,$this->_hub,$streamKey);
    }

    public function get($streamKey){
        $eKey=Utils::base64UrlEncode($streamKey);
        $url = $this->_baseURL . "/streams/".$eKey;
        try {
            $disabledTill=$this->_transport->send(HttpRequest::GET, $url);
        }catch(\Exception $e){
            return $e;
        }
        return new \Pili\Stream($this->_transport,$this->_hub,$streamKey,$disabledTill);
    }

    private function _list($live, $prefix,$limit, $marker){
        $url = sprintf("%s/streams?liveonly=%s&prefix=%s&limit=%d&marker=%s", $this->_baseURL, $live, $prefix, $limit, $marker);
        echo $url,"\n";
        try {
            $ret=$this->_transport->send(HttpRequest::GET, $url);
        }catch(\Exception $e){
            return $e;
        }
        $keys=array();
        foreach ($ret["items"] as $item){
            array_push($keys,$item["key"]);
        }
        $ret=array();
        $ret["keys"]=$keys;
        $ret["omarker"]=$ret["marker"];
        return $ret;
    }

    public function listLiveStreams($prefix, $limit, $marker){
        return $this->_list("true", $prefix, $limit, $marker);
    }

    public function listStreams($prefix, $limit, $marker){
        return $this->_list("false", $prefix, $limit, $marker);
    }
}

?>