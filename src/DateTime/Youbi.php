<?php
namespace FrUtility\DateTime;

use FrUtility\Extended\TextKit;

class Youbi
{
    /**
     * 曜日情報をフォーマットする。
     *
     * @param string             $format   フォーマット対象の文字列
     * @param \DateTimeInterface $datetime 日付オブジェクト
     *
     * @return string フォーマット後の文字列
     */
    public static function formatYoubi(string $format, \DateTimeInterface $datetime): string
    {
        // デフォルトのパラメータを設定
        $params = [
            'W' => self::getYoubi($datetime)
        ];

        // フォーマットパラメータを適用して文字列を取得
        $formattedString = TextKit::formatText($format, $params, false);

        return $formattedString;
    }

    /**
     * 日付から曜日を取得する。
     *
     * @param \DateTimeInterface $datetime 日付オブジェクト
     *
     * @return string 曜日
     */
    public static function getYoubi(\DateTimeInterface $datetime): string
    {
        $week = ['日', '月', '火', '水', '木', '金', '土'];
        $youbi = $week[$datetime->format('w')];

        return $youbi;
    }
}
