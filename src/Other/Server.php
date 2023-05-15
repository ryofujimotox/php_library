<?php
namespace FrUtility\Other;

class Server
{
    /**
     * 外部IPかどうかを判定する
     *
     * @param array $allow_ips
     *   許可するIPアドレスの配列。CIDR形式のサブネットマスクを含むことができます。
     *   例: [
     *     'xxx.xxx.xx.x/23',
     *     'xxx.xxx.xxx.xxx',
     *   ];
     *
     * @return bool trueなら外部IP、falseなら内部IP
     */
    public static function isExternalIp(array $allow_ips): bool
    {
        $remote_ip = self::getIp();

        foreach ($allow_ips as $accept) {
            $address = explode('/', $accept);
            if (isset($address[1])) {
                [$accept_ip, $mask] = $address;
                $accept_long = ip2long($accept_ip) >> (32 - $mask);
                $remote_long = ip2long($remote_ip) >> (32 - $mask);
                if ($accept_long === $remote_long) {
                    return false;
                }
            } else {
                $address = $address[0];
                if ($address === $remote_ip) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * クライアントのIPアドレスを取得します。
     *
     * @return string クライアントのIPアドレスが文字列で返されます。
     */
    public static function getIp(): string
    {
        $ip = '';
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipArray = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = $ipArray[0];
        }
        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * リクエスト元URLを取得します。
     *
     * @return string リクエスト元のURLが文字列で返されます。
     */
    public static function getReferrer(): string
    {
        $referer = '';
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];
        }
        return $referer;
    }
}
