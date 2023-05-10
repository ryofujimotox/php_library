<?php
namespace FrUtility\Extended;

use \DateTime;
use \DateTimeZone;
use \Exception;

class DateTimeKit
{
    /**
     * タイムスタンプとタイムゾーンから DateTime オブジェクトを生成する
     *
     * @param int $timestamp Unix タイムスタンプ
     * @param string $timezone タイムゾーン識別子 (デフォルトは Asia/Tokyo)
     * @return DateTime 生成された DateTime オブジェクト
     */
    public static function createFromTimestamp(int $timestamp, string $timezone = 'Asia/Tokyo'): DateTime
    {
        try {
            // DateTime クラスの新しいインスタンスを作成する
            $dateTime = new DateTime();

            // タイムスタンプを設定する
            $dateTime->setTimestamp($timestamp);
        } catch(\Throwable $ex) {
            throw new Exception('timestampが不正です');
        }

        try {
            // タイムゾーンを設定する
            $dateTime->setTimeZone(new DateTimeZone($timezone));
        } catch(\Throwable $ex) {
            throw new Exception('timezoneが不正です');
        }

         // 新しい DateTime オブジェクトを返す
         return $dateTime;
    }
}
