<?php
namespace FrUtility\DateTime;

use \DateTime as BaseDateTime;
use \DateTimeZone;
use \Exception;

class Timestamp extends BaseDateTime
{
    /**
    * タイムスタンプとタイムゾーンから DateTime オブジェクトを生成する
    *
    * @param int $timestamp Unix タイムスタンプ
    * @param string $timezone タイムゾーン識別子 (デフォルトは Asia/Tokyo)
    */
    public static function setSafeTimestamp(int $timestamp, string $timezone = 'Asia/Tokyo')
    {
        try {
            // タイムスタンプを設定する
            $this->setTimestamp($timestamp);
        } catch(\Throwable $ex) {
            throw new Exception('timestampが不正です');
        }

        try {
            // タイムゾーンを設定する
            $this->setTimeZone(new DateTimeZone($timezone));
        } catch(\Throwable $ex) {
            throw new Exception('timezoneが不正です');
        }

         // 新しい DateTime オブジェクトを返す
         return $this;
    }
}
