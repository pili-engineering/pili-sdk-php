<?php
namespace Pili;

use \Qiniu\Utils;
use \Qiniu\HttpRequest;
use \Pili\Config;

final class Api
{
    private static function _getApiBaseUrl()
    {
        $cfg = Config::getInstance();
        $protocal = $cfg->USE_HTTPS === true ? "https" : "http";
        $url = sprintf("%s://%s/%s/", $protocal, $cfg->API_HOST, $cfg->API_VERSION);
        return $url;
    }

    public static function createStream($transport, $hubName, $title = NULL, $publishKey = NULL, $publishSecurity = NULL)
    {
        $url = self::_getApiBaseUrl() . 'streams';
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
        return $transport->send(HttpRequest::POST, $url, $body);
    }

    public static function getStream($transport, $streamId)
    {
        $url  = self::_getApiBaseUrl() . "streams/$streamId";
        return $transport->send(HttpRequest::GET, $url);
    }

    public static function listStreams($transport, $hubName, $marker = NULL, $limit = NULL, $title = NULL, $status = NULL, $idonly = NULL)
    {
        $url = self::_getApiBaseUrl() . "streams?hub=$hubName";
        if (!empty($marker)) {
            $url .= "&marker=$marker";
        }
        if (!empty($limit)) {
            $url .= "&limit=$limit";
        }
        if (!empty($title)) {
            $url .= "&title=$title";
        }
        if (!empty($status)) {
        	$url .= "&status=$status";
        }
        if (!empty($idonly)) {
        	$url .= "&idonly";
        }
        return $transport->send(HttpRequest::GET, $url);
    }

    public static function streamStatus($transport, $streamId)
    {
        $url = self::_getApiBaseUrl() . "streams/$streamId/status";
        return $transport->send(HttpRequest::GET, $url);
    }

    public static function streamUpdate($transport, $streamId, $options = array())
    {
        $url  = self::_getApiBaseUrl() . "streams/$streamId";
        $params = array();
        $keys = array('publishKey', 'publishSecurity', 'disabled');
        foreach ($keys as $key) {
            if (isset($options[$key])) {
                $params[$key] = $options[$key];
            }
        }
        $body = json_encode($params);
        $body = empty($body) ? '{}' : $body;
        return $transport->send(HttpRequest::POST, $url, $body);
    }

    public static function streamDelete($transport, $streamId)
    {
        $url = self::_getApiBaseUrl() . "streams/$streamId";
        return $transport->send(HttpRequest::DELETE, $url);
    }

    public static function streamSegments($transport, $streamId, $startTime = NULL, $endTime = NULL, $limit = NULL)
    {
        $url = self::_getApiBaseUrl() . "streams/$streamId/segments";
        if (!empty($startTime)) {
            $url .= "?start=$startTime";
        }
        if (!empty($endTime)) {
            $url .= "&end=$endTime";
        }
        if (!empty($limit)) {
            $url .= "&limit=$limit";
        }
        return $transport->send(HttpRequest::GET, $url);
    }

    public static function streamSaveAs($transport, $streamId, $name, $format = NULL, $start = NULL, $end = NULL, $notifyUrl = NULL, $pipeline = NULL)
    {
        $url = self::_getApiBaseUrl() . "streams/$streamId/saveas";
        $params = array(
            'name'   => $name,
        );
        if (!empty($format)) {
            $params['format'] = $format;
        }
        if (!empty($start)) {
            $params['start'] = $start;
        }
        if (!empty($end)) {
            $params['end'] = $end;
        }
        if (!empty($notifyUrl)) {
            $params['notifyUrl'] = $notifyUrl;
        }
        if (!empty($pipeline)) {
            $params['pipeline'] = $pipeline;
        }
        $body = json_encode($params);
        return $transport->send(HttpRequest::POST, $url, $body);
    }

    public static function streamSnapshot($transport, $streamId, $name, $format, $time = NULL, $notifyUrl = NULL, $pipeline = NULL)
    {
        $url = self::_getApiBaseUrl() . "streams/$streamId/snapshot";
        $params = array(
            'name'   => $name,
            'format' => $format,
        );
        if (!empty($time)) {
            $params['time'] = $time;
        }
        if (!empty($notifyUrl)) {
            $params['notifyUrl'] = $notifyUrl;
        }
        if (!empty($pipeline)) {
            $params['pipeline'] = $pipeline;
        }
        $body = json_encode($params);
        return $transport->send(HttpRequest::POST, $url, $body);
    }

    public static function streamAvailable($transport, $streamId, $available, $disabledTill = NULL)
    {
        $url = self::_getApiBaseUrl() . "streams/$streamId/available";
        $params = array('available' => $available);
        if (!empty($disabledTill)) {
            $params['disabledTill'] = $disabledTill;
        }
        $body = json_encode($params);
        return $transport->send(HttpRequest::POST, $url, $body);
    }
}
?>
