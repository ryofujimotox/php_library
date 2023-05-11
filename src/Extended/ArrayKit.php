<?php
namespace FrUtility\Extended;

class ArrayKit
{
    /**
     * ２つの配列が等しいか確認する。(順序も)
     *
     * @param array $array1 比較される最初の配列
     * @param array $array2 比較される２番目の配列
     *
     * @return bool 等しい場合はtrue、さもなければfalseが返されます。
     */
    public static function are_same(array $array1, array $array2): bool
    {
        $mergedArray = array_merge(
            array_diff_assoc($array1, $array2),
            array_diff_assoc($array2, $array1)
        );
        return empty(array_unique($mergedArray));
    }

    /**
     * ２つの配列が同じ値を持っているか確認する。(順序不問)
     *
     * @param array $array1 比較する最初の配列
     * @param array $array2 比較する2番目の配列
     * @return bool ２つの配列が同じ値を持っている場合は true、それ以外の場合は false を返す
     */
    public static function are_match(array $array1, array $array2): bool
    {
        $difference = self::difference($array1, $array2);
        return empty($difference);
    }

    /**
     * 2つの配列間の差分を計算する関数
     *
     * @param array $array1 値を比較する配列1
     * @param array $array2 値を比較する配列2
     *
     * @return array 差分が含まれる配列
     */
    public static function difference(array $array1, array $array2): array
    {
        // 配列1から配列2にある要素と、配列2から配列1にある要素を取得して、最終的な結果を生成します。
        $result = array_merge(array_diff($array1, $array2), array_diff($array2, $array1));

        // 結果に重複があればそれを削除します。
        return array_unique($result);
    }

    /**
     * 先頭から指定された数の要素と、末尾から指定された数の要素を、結合して配列を返す。
     *
     * @param array $array 切り取る元の配列。
     * @param int $first 先頭から残す要素数。
     * @param int $last 末尾から残す要素数。
     *
     * @sample [ [1, 2, 3], 1, 1 ] => [1, 3]
     *
     * @return array 先頭から残す要素と末尾から残す要素を結合した配列を返す。
     */
    public static function slice_firla(array $array, int $first, int $last): array
    {
        return array_merge(
            array_slice($array, 0, $first),
            array_slice($array, -1 * $last)
        );
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
     * 多次元配列をフラット化する
     *
     * @param array $array フラット化する多次元配列 [ [], [num1], [], [[num2]], [] ]
     * @return array フラット化された配列を返す [ num1, num2 ]
     */
    public static function flatten(array $array): array
    {
        $flattenedArray = [];

        foreach ($array as $data) {
            // $data が空の場合は無視する
            if ($data) {
                // $data が配列である場合、再帰的にフラット化する
                if (is_array($data)) {
                    $flattenedArray = array_merge($flattenedArray, self::flatten($data));
                } else {
                    // $data が配列でない場合、フラット化した配列に値を追加する
                    $flattenedArray[] = $data;
                }
            }
        }

        return $flattenedArray;
    }
}
