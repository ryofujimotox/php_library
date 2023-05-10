<?php
namespace FrUtility\Extended;

class TextKit
{
    /**
     * テキストをフォーマットする。
     *
     * @param string $text フォーマット対象のテキスト。 ( X集合ですW。 )
     * @param array $params 置換するパラメーターの連想配列。 ( [ "X" => "全", W => "(笑)" ] )
     * @param bool $useBrackets 置換対象のパラメーター名に{}を使用するかどうか。
     * @return string フォーマットされたテキスト。 ( 全集合です(笑)。 )
     */
    public static function formatText(string $text, array $params, bool $useBrackets = false): string
    {
        // パラメーターを置換する。
        $replacePairs = [];
        foreach ($params as $key => $value) {
            $target = $useBrackets ? '{' . $key . '}' : $key;
            $replacePairs[$target] = $value;
        }

        // テキストを置換する。
        $text = strtr($text, $replacePairs);

        // フォーマットされたテキストを返す。
        return $text;
    }
}
