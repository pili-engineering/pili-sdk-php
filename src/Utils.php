<?php
namespace Pili;

final class Utils
{
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

    public static function digest($secret_key, $data)
    {
        return hash_hmac('sha1', $data, $secret_key, true);
    }

    public static function sign($access_key, $secret_key, $data)
    {
        $digest = self::digest($secret_key, $data);
        return $access_key . ':' . self::base64UrlEncode($digest);
    }

    public static function signRequest($access_key, $secret_key, $url, $body = '')
    {
        $url = parse_url($url);
        $data = '';
        if (isset($url['path'])) {
            $data = $url['path'];
        }
        if (isset($url['query'])) {
            $data .= '?' . $url['query'];
        }
        $data .= "\n";
        if (strlen($body)) {
            $data .= $body;
        }
        return self::sign($access_key, $secret_key, $data);
    }
}
