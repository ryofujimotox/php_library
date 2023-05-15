<?php
namespace FrUtility\DateTime;

use FrUtility\Extended\TextKit;
use DateTimeInterface;

class Youbi
{
    /**
     * 曜日情報をフォーマットする。
     *
     * @param string             $format   フォーマット対象の文字列
     * @param DateTimeInterface $datetime 日付オブジェクト
     *
     * @return string フォーマット後の文字列
     */
    public static function format(string $format, DateTimeInterface $datetime): string
    {
        // デフォルトのパラメータを設定
        $params = [
            'W' => self::getYoubi($datetime)
        ];

        // フォーマットパラメータを適用して文字列を取得
        $formattedString = TextKit::format($format, $params, false);

        return $formattedString;
    }

    /**
     * DateTimeから曜日を取得する。
     *
     * @param DateTimeInterface $datetime 日付オブジェクト
     *
     * @return string 曜日
     */
    public static function getYoubi(DateTimeInterface $datetime): string
    {
        $week = ['日', '月', '火', '水', '木', '金', '土'];
        $youbi = $week[$datetime->format('w')];

        return $youbi;
    }
}
