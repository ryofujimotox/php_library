<?php
namespace FrUtility\Extended;

class ArrayKit
{
    /**
    *
    * 配列のうち、最初と最後の要素だけで再構成する
    *
    */
    public static function slice_firla(array $array, int $first = 0, int $last = 0): array
    {
        return array_merge(
            array_slice($array, 0, $first),
            array_slice($array, -1 * $last)
        );
    }

    /**
     *
     * 同一の配列であることを検証する
     * (順番も)
     *
     */
    public static function are_same(array $array1, array $array2): bool
    {
        $diff = array_merge(array_diff_assoc($array1, $array2), array_diff_assoc($array2, $array1));
        return empty(array_unique($diff));
    }

    /**
     *
     * 同一の配列であることを検証する
     * (順番は見ない)
     *
     */
    public static function are_match(array $array1, array $array2): bool
    {
        $diff = self::difference($array1, $array2);
        return empty($diff);
    }

    /**
     *
     * 配列同士の差分を取得する
     *
     */
    public static function difference(array $arr1,  array $arr2): array
    {
        $diff = array_merge(array_diff($arr1, $arr2), array_diff($arr2, $arr1));
        return array_unique($diff);
    }

    /**
    *
    * 指定した値通りに並び替える
    * インデックスを維持しない。 してもいいかも
    *
    * @param array $array 配列 ( ["hokkaido", "aomori",,,] )
    * @param array $sticky 配列に含まれている値 ( ["tokyo", "aomori"] )
    *
    * @return array stickyを上に持っていった配列 ( ["tokyo", "aomori", "hokkaido",,,] )
    *
    */
    public static function sort_by_values(array $array, array $sticky): array
    {
        $_diff = array_diff($array, $sticky);
        $_intersect = array_intersect($sticky, $array);
        $merge = array_merge($_intersect, $_diff);
        return $merge;
    }

    /**
     *
     * 子要素だけで再構成する
     * @param val = [ [], [num1], [], [[num2]], [] ]
     *
     * @return Array = [num1, [num2]]
     *
     */
    public static function flatten(array $array): array
    {
        $tmp = [];
        foreach ($array as $data) {
            if ($data) {
                if (is_array($data)) {
                    $tmp = array_merge($tmp, $data);
                } else {
                    $tmp[] = $data;
                }
            }
        }
        return $tmp;
    }
}
