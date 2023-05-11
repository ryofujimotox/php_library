<?php
namespace FrUtility\DateTime;

use FrUtility\DateTime\DateTime;
use DateTimeZone;
use Exception;

/**
 * タイムスタンプからDateTimeオブジェクトを生成するクラス
 */
class DateTimeFromTimestamp extends DateTime
{
    /**
     * タイムスタンプに基づいてDateTimeオブジェクトを生成する
     *
     * @param int $timestamp タイムスタンプ
     * @param string $timezone タイムゾーン (デフォルト: 'Asia/Tokyo')
     */
    public function __construct(int $timestamp, string $timezone = 'Asia/Tokyo')
    {
        parent::__construct();

        // タイムスタンプの設定
        $this->setTimestampWithSafe($timestamp, $timezone);
    }

    /**
    * タイムスタンプとタイムゾーンから DateTime オブジェクトを生成する
    *
    * @param int $timestamp Unix タイムスタンプ
    * @param string $timezone タイムゾーン識別子 (デフォルトは Asia/Tokyo)
    * @throws Exception 不正な引数の場合に例外をスロー
    */
    public function setTimestampWithSafe(int $timestamp, string $timezone = 'Asia/Tokyo'): DateTime
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

        return $this;
    }
}
