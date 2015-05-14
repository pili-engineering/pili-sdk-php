<?php

namespace Pili;

final class Utils
{

    const USER_AGENT         = 'pili-sdk-php';
    const DIGEST_AUTH_PREFIX = 'Qiniu';

    public static function getUserAgent($version)
    {
        $ua = self::USER_AGENT . '/' . $version;
        if (extension_loaded('curl')) {
            $curlVersion = curl_version();
            $ua .= ' curl/' . $curlVersion['version'];
        }
        $ua .= ' PHP/' . PHP_VERSION;
        return $ua;
    }

    public static function base64UrlEncode($str)
    {
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($str));
    }

    public static function base64UrlDecode($str)
    {
        $find = array('-', '_');
        $replace = array('+', '/');
        return base64_decode(str_replace($find, $replace, $str));
    }

    public static function digest($secret, $data)
    {
        return hash_hmac('sha1', $data, $secret, true);
    }

    public static function sign($secret, $data)
    {
        return self::base64UrlEncode(self::digest($secret, $data));
    }

    public static function signRequest($accessKey, $secretKey, $method, $url, $contentType, $body)
    {
        $url = parse_url($url);
        $data = '';
        if (!empty($url['path'])) {
            $data = $method . ' ' . $url['path'];
        }
        if (!empty($url['query'])) {
            $data .= '?' . $url['query'];
        }
        if (!empty($url['host'])) {
            $data .= "\nHost: " . $url['host'];
            if (isset($url['port'])) {
                $data .= ':' . $url['port'];
            }
        }
        if (!empty($contentType)) {
            $data .= "\nContent-Type: " . $contentType;
        }
        $data .= "\n\n";
        if (!empty($body)) {
            $data .= $body;
        }
        return self::DIGEST_AUTH_PREFIX . ' ' . $accessKey . ':' . self::sign($secretKey, $data);
    }

}

?>
