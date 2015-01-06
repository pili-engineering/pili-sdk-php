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

    public static function digest($secret, $data)
    {
        return hash_hmac('sha1', $data, $secret, true);
    }

    public static function sign($id, $secret, $data)
    {
        $digest = $this->digest($secret, $data);
        return $id . ':' . $this->base64UrlEncode($digest);
    }

    public static function signRequest($id, $secret, $url, $body = '')
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
        return $this->sign($id, $secret, $data);
    }
}
