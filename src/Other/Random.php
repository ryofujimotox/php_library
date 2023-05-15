<?php
namespace FrUtility\Other;

use FrUtility\Extended\ArrayKit;

class Random
{
    /**
     * ランダムな文字列を生成する
     *
     * @param int $length 生成する文字列の長さ
     * @return string 生成されたランダムな文字列
     */
    public static function string(int $length = 20): string
    {
        return self::generate($length, range('a', 'z'), range('A', 'Z'), range('0', '9'));
    }

    /**
     * ランダムな数値を生成する
     *
     * @param int $length 生成する数値の桁数
     * @param bool $excludeLeadingZero 先頭のゼロを除外するかどうか
     * @return string 生成されたランダムな数値
     */
    public static function int(int $length = 6, bool $excludeLeadingZero = true): string
    {
        $result = '';
        if ($length <= 0) {
            return $result;
        }

        // 先頭のゼロを除外
        if ($excludeLeadingZero) {
            $result .= rand(1, 9);
            $length -= 1;
        }

        $result .= self::generate($length, range('0', '9'));
        return $result;
    }

    /**
     * 配列からランダムな要素を取得する
     *
     * @param array $array 取得する要素を含む配列
     * @return mixed ランダムな要素
     */
    public static function arrayValue(array $array)
    {
        return $array[self::arrayKey($array)];
    }

    /**
     * 配列のインデックスからランダムな整数値を生成する
     *
     * @param array $array インデックスを取得する配列
     * @return int ランダムな整数値
     */
    public static function arrayKey(array $array)
    {
        $array = array_keys($array);
        return $array[rand(0, count($array) - 1)];
    }

    /**
     * 指定された長さのランダムな文字列を生成します。
     *
     * @param int $length 生成する文字列の長さ
     * @param array ...$ranges 文字列の生成に使用する文字の範囲（配列で指定します）
     * @return string 生成されたランダムな文字列
     */
    private static function generate(int $length, ...$ranges): string
    {
        $result = '';
        $characters = ArrayKit::flatten($ranges);
        for ($i = 0; $i < $length; $i++) {
            $result .= self::arrayValue($characters);
        }
        return $result;
    }
}
