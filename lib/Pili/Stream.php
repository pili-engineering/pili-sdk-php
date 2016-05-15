<?php
namespace Pili;

use \Qiniu\Utils;
use \Qiniu\HttpRequest;
use \Pili\Transport;

class Stream
{
    private $_transport;
    private $_hub;
    private $_key;
    private $_baseUrl;
    private $_disabledTill;

    public function __construct($transport, $hub, $key, $disabledTill=0)
    {
        $this->_transport = $transport;
        $this->_hub = $hub;
        $this->_key = $key;
        $this->_disabledTill=$disabledTill;

        $cfg = Config::getInstance();
        $protocal = $cfg->USE_HTTPS === true ? "https" : "http";
        $this->_baseUrl = sprintf("%s://%s/%s/hubs/%s/streams/%s", $protocal, $cfg->API_HOST, $cfg->API_VERSION, $this->_hub, Utils::base64UrlEncode($this->_key));
    }


    public function disable()
    {
        $url = $this->_baseUrl . "/disabled";
        $params['disabledTill'] = -1;
        $body = json_encode($params);
        return $this->_transport->send(HttpRequest::POST, $url, $body);
    }

    public function enable()
    {
        $url = $this->_baseUrl . "/disabled";
        $params['disabledTill'] = 0;
        $body = json_encode($params);
        return $this->_transport->send(HttpRequest::POST, $url, $body);
    }

    public function liveStatus()
    {
        $url = $this->_baseUrl . "/live";
        return $this->_transport->send(HttpRequest::GET, $url);
    }

    public function historyRecord($start, $end)
    {
        $url = $this->_baseUrl . "/historyrecord";
        $flag = "?";
        if (!empty($start)) {
            $url = $url . $flag . "start=" . $start;
            $flag = "&";
        }
        if (!empty($end)) {
            $url = $url . $flag . "end=" . $end;
        }
        return $this->_transport->send(HttpRequest::GET, $url);
    }

    public function save($start=NULL, $end=NULL)
    {
        $url = $this->_baseUrl . "/saveas";
        if (!empty($start)) {
            $params['start'] = $start;
        }
        if (!empty($end)) {
            $params['end'] = $end;
        }
        $body = json_encode($params);
        return $this->_transport->send(HttpRequest::POST, $url, $body);
    }

    //----------------url
    public function RTMPPublishURL($domain, $hub, $streamKey, $expireAfterSeconds, $accessKey, $secretKey)
    {
        $expire = time() + $expireAfterSeconds;
        $path = sprintf("/%s/%s?e=%d", $hub, $streamKey, $expire);
        $token = $accessKey . ":" . Utils::sign($secretKey, $path);
        return sprintf("rtmp://%s%s&token=%s", $domain, $path, $token);
    }

    public function RTMPPlayURL($domain, $hub, $streamKey)
    {
        return sprintf("rtmp://%s/%s/%s", $domain, $hub, $streamKey);
    }

    public function HLSPlayURL($domain, $hub, $streamKey)
    {
        return sprintf("http://%s/%s/%s.m3u8", $domain, $hub, $streamKey);
    }

    public function HDLPlayURL($domain, $hub, $streamKey)
    {
        return sprintf("http://%s/%s/%s.flv", $domain, $hub, $streamKey);
    }

    public function SnapshotPlayURL($domain, $hub, $streamKey)
    {
        return sprintf("http://%s/%s/%s.jpg", $domain, $hub, $streamKey);
    }
}

?>
