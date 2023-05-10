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

    /**
     * 日付を指定されたフォーマットでフォーマットする。
     *
     * @param DateTime $date    フォーマットする日付オブジェクト。
     * @param string   $format  フォーマット文字列。 ( X集合ですW。Y年のいつかにやるW )
     * @param array    $params  パラメータ ( [ "X" => "全", W => "(笑)" ] )
     * @return string フォーマットされた日付文字列。 ( 全集合です(笑)。2023年 )
     */
    public static function formatDateWithText(DateTime $date, string $format, array $params): string
    {
        $text = $date->format($format);

        // フォーマットされた日付文字列を返す。
        return TextKit::formatText($text, $params);
    }
}
