<?php

namespace Phphub\Handler;

use GuzzleHttp\Client;

class SlugTranslate
{
    public static function translate($text)
    {
        if (static::isEnglish($text)) {
            return $text;
        }

        $http = new Client;

        $api = 'http://api.fanyi.baidu.com/api/trans/vip/translate?';
        $appid = config('services.baidu_translate.appid');
        $salt = time();
        $key = config('services.baidu_translate.key');

        // http://api.fanyi.baidu.com/api/trans/product/apidoc
        // appid+q+salt+密钥 的MD5值
        $sign = md5($appid. $text . $salt . $key);

        $query = http_build_query([
            "q"     =>  $text,
            "from"  => "zh",
            "to"    => "en",
            "appid" => $appid,
            "salt"  => $salt,
            "sign"  => $sign,
        ]);
        $url = $api.$query;

        $response = $http->get($url);

        $result = json_decode($response->getBody(), true);
        if (isset($result['trans_result'][0]['dst'])) {
            return str_slug($result['trans_result'][0]['dst']);
        }
        return false;
    }

    public static function isEnglish($text)
    {
        if (preg_match("/\p{Han}+/u", $text)) {
            return false;
        }

        return true;
    }

}
