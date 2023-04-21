<?php
namespace FrUtility\Other;

require_once dirname(__FILE__) . '/../../lib/CryptLib/bootstrap.php';
use CryptLib\MAC\Implementation\CMAC;

class Encrypt
{
    /**
     *
     * AES-CMAC署名を行う
     *
     * よくわからないけど、sesameで使った。
     * https://doc.candyhouse.co/ja/SesameAPI
     *
     * @param string $message hex2bin() をする必要あるかも
     * @param string $key hex2bin() をする必要あるかも
     *
     * @return string 暗号化したテキスト
     */
    public static function AES_CMAC(string $message, string $key): string
    {
        // $msg_content = "test";
        // $msg = substr(strrev(hex2bin(dechex($msg_content))), 1);
        // $key = hex2bin($key);

        $hasher = new CMAC();
        $encrypt = $hasher->generate($message, $key);
        return $encrypt;
    }
}
