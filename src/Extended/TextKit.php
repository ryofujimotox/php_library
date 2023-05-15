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
    public static function format(string $text, array $params, bool $useBrackets = false): string
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

    /**
     * 渡された文字列の先頭からマッチするカナ文字を取得する
     *
     * @param string $string カナ文字を取得する文字列 例: り
     * @return string 先頭にマッチしたカナ文字 例: ら
     */
    public static function getInitialOfKana(string $hira): string
    {
        $kana = array(
            'あ' => '[あ-お]',
            'か' => '[か-こが-ご]',
            'さ' => '[さ-そざ-ぞ]',
            'た' => '[た-とだ-ど]',
            'な' => '[な-の]',
            'は' => '[は-ほば-ぼぱ-ぽ]',
            'ま' => '[ま-も]',
            'や' => '[や-よ]',
            'ら' => '[ら-ろ]',
            'わ' => '[わ-ん]',
            '他' => '.*'
        );
        foreach ($kana as $initial => $pattern) {
            if (preg_match('/^' . $pattern . '/u', $hira)) {
                return $initial;
            }
        }
        return '';
    }

    /**
     * 文字列を分割し、それらの配列を組み合わせた結果を返す
     *
     * @param array $separators 分割する文字列のリスト
     * @param string $string 分割される文字列
     * @return array 分割された文字列が組み合わされた結果の配列
     */
    public static function multiExplode(array $separators, string $string): array
    {
        // 区切り文字が指定されていない場合は、元の文字列を単一要素の配列に変換して返す
        if (!is_array($separators) || empty($separators)) {
            if ($string) {
                return [$string];
            } else {
                return [];
            }
        }

        // 区切り文字で文字列を分割して、分割後の配列を返す
        $pattern = '/(' . implode('|', $separators) . ')/';
        $result = preg_split($pattern, $string, -1, PREG_SPLIT_NO_EMPTY);
        return $result;
    }
}
