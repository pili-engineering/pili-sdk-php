<?php
namespace Pili;

use Pili\Conf;
use Pili\Utils;
use Pili\HttpRequest;
use Pili\Auth;

final class Api
{
    private static function _getApiBaseUrl()
    {
        $protocal = Conf::getInstance()->USE_HTTPS === true ? "https" : "http";
        $url = sprintf("%s://%s/%s/", $protocal, Conf::getInstance()->API_HOST, Conf::getInstance()->API_VERSION);
        return $url;
    }

    public static function createStream($auth, $hubName, $title = NULL, $publishKey = NULL, $publishSecurity = NULL)
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
        return $auth->request(HttpRequest::POST, $url, $body);
    }

    public static function getStream($auth, $streamId)
    {
        $url  = self::_getApiBaseUrl() . "streams/$streamId";
        return $auth->request(HttpRequest::GET, $url);
    }

    public static function listStreams($auth, $hubName, $marker = NULL, $limit = NULL, $title = NULL)
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
        return $auth->request(HttpRequest::GET, $url);
    }

    public static function streamStatus($auth, $streamId)
    {
        $url = self::_getApiBaseUrl() . "streams/$streamId/status";
        return $auth->request(HttpRequest::GET, $url);
    }

    public static function streamUpdate($auth, $streamId, $options = array())
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
        return $auth->request(HttpRequest::POST, $url, $body);
    }

    public static function streamDelete($auth, $streamId)
    {
        $url = self::_getApiBaseUrl() . "streams/$streamId";
        return $auth->request(HttpRequest::DELETE, $url);
    }

    public static function streamSegments($auth, $streamId, $startTime = NULL, $endTime = NULL, $limit = NULL)
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
        return $auth->request(HttpRequest::GET, $url);
    }

    public static function streamSaveAs($auth, $streamId, $name, $format, $start, $end, $notifyUrl = NULL)
    {
        $url = self::_getApiBaseUrl() . "streams/$streamId/saveas";
        $params = array(
            'name'   => $name,
            'format' => $format,
            'start'  => $start, 
            'end'    => $end,
        );
        if (!empty($notifyUrl)) {
            $params['notifyUrl'] = $notifyUrl;
        }
        $body = json_encode($params);
        return $auth->request(HttpRequest::POST, $url, $body);
    }

    public static function streamSnapshot($auth, $streamId, $name, $format, $time = NULL, $notifyUrl = NULL)
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
        $body = json_encode($params);
        return $auth->request(HttpRequest::POST, $url, $body);
    }
}
?>