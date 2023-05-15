<?php
namespace FrUtility\Extended;

use FrUtility\Extended\ArrayKit;

class DictionaryKit
{
    /**
     *
     * 多次元連想配列を、指定のキーの指定の値の順番通りに並べる
     * （ 指定する値は完全でなくていい ）
     *
     * @param array $dictionary 多次元連想配列 ( {〇〇=>〇〇, age=>50}, {〇〇=>〇〇, age=>2}, {〇〇=>〇〇, age=>99},  )
     * @param string $key 対象のキー ( age )
     * @param array $values 値で順序を決める ( [ 99 ] )
     *
     * @param array $dictionary ( {〇〇=>〇〇, age=>99}, {〇〇=>〇〇, age=>2}, {〇〇=>〇〇, age=>50},  )
     *
     */
    public static function sort_by_values(array $dictionary, string $key, array $values = []): array
    {
        // sticky並び替え後の、値リスト
        $order_value = ArrayKit::sort_by_values(array_column($dictionary, $key), $values);

        // 値リストの順に並べ変える
        $dictionary = self::sort_by_full_values($dictionary, $key, $order_value);
        return $dictionary;
    }

    /**
     *
     * sort_by_valuesの本体。
     * 多次元連想配列を、指定のキーの指定の値の順番通りに並べる
     *  ( 全ての値が必要 )
     *
     * @param array $dictionary 多次元連想配列
     * @param string $key 対象のキー
     * @param array $order 値で順序を決める
     *
     * @param array 順序変更後の多次元連想配列
     *
     */
    public static function sort_by_full_values(array $dictionary, string $key, array $order): array
    {
        $_dictionary = $dictionary;
        usort($_dictionary, function ($a, $b) use ($key, $order) {
            return array_search($a[$key], $order) > array_search($b[$key], $order);
        });
        return $_dictionary;
    }

    /**
     *
     * 連想配列のキーでグループする
     *
     * @param array $dictionary 多次元連想配列  例: [ {pref:"tokyo", age:1}, {pref:"tokyo", age:2}, {pref:"kago", age:3} ];
     * @param string $group_key グループするキー 例: "pref"
     * @param string $key 連想配列の値だけで再構築する
     *
     * @return array 例: [ "tokyo" => [{pref:"tokyo", age:1}, {pref:"tokyo", age:2}], "kago" => [{pref:"kago", age:3}] ];
     */
    public static function groupup(array $dictionary, string $group_key, ?string $key): array
    {
        return array_reduce($dictionary, function (array $groups, array $row) use ($group_key, $key) {
            if ($key) {
                $groups[$row[$group_key]][] = $row[$key];
            } else {
                $groups[$row[$group_key]][] = $row;
            }
            return $groups;
        }, []);
    }

    /**
     * 辞書から除外するキーをフィルタリングする
     *
     * @param array $excludes 除外するキーの配列 [ 削除する鍵 ]
     * @param array $dictionary フィルタリングする辞書配列 [ 削除する鍵 => value, 残す鍵 => value2 ]
     * @return array [ 残す鍵 => value2 ]
     */
    public static function filterExcludedKeys(array $excludes, array $dictionary): array
    {
        return array_filter($dictionary, function ($key) use ($excludes) {
            return !in_array($key, $excludes, true);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * 配列から指定されたキーをフィルタリングする
     *
     * @param array $keys フィルタリングするキーの配列
     * @param array $array フィルタリングされる配列
     * @return array フィルタリングされた配列
     */
    public static function filterByKeys(array $keys, array $array): array
    {
        return array_filter(
            $array,
            function ($key) use ($keys) {
                return in_array($key, $keys);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * 渡された配列の各要素をキーとして、値が元の値と同じになるように新しい連想配列を作成する
     *
     * @param array $values キーと値の両方ともに同じ値を持つ配列
     * @return array 新しい連想配列
     */
    public static function createFromValues(array $values): array
    {
        return array_combine($values, $values);
    }

    /**
     * 多次元配列から指定されたキーに対応する値を抽出する。
     *
     * @param array $array 値を抽出する配列。 [test => [1], test2 => [ test => [2, 3] ]]
     * @param string $key 抽出する値のキー。 "test"
     * @return array キーに対応する値の配列。キーが存在しない場合は空の配列を返す。 [ 1, 2, 3 ]
     */
    public static function extractValueByKey(array $array, string $key)
    {
        $search = function ($arrayKey, $arrayValue) use ($key, &$search) {
            if ($arrayKey === $key) {
                return $arrayValue;
            }
            if (is_array($arrayValue)) {
                return ArrayKit::flatten(self::array_map_with_key($search, $arrayValue));
            }
        };
        return ArrayKit::flatten(self::array_map_with_key($search, $array));
    }

    /**
     * 配列の各要素に指定したコールバック関数を適用した結果を返す。
     *
     * @param callable $callback 配列の各要素に適用するコールバック関数。
     *                           第一引数に配列のキー、第二引数に配列の値が渡される。
     *                           無名関数($配列鍵, $配列値){ }
     * @param array $array 適用する配列。
     * @return array 配列の各要素にコールバック関数を適用した結果。
     */
    public static function array_map_with_key(callable $callback, array $array): array
    {
        // 配列の各要素にコールバック関数を適用して、$resultに格納する
        $result = array_map($callback, array_keys($array), $array);

        return $result;
    }
}
