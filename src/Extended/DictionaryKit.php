<?php
namespace FrUtility\Extended;

use FrUtility\Extended\ArrayKit;

class DictionaryKit {
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
    public static function sort_by_values(array $dictionary, string $key, array $values = []): array {
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
    public static function sort_by_full_values(array $dictionary, string $key, array $order): array {
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
    public static function groupup(array $dictionary, string $group_key, ?string $key): array {
        return array_reduce($dictionary, function (array $groups, array $row) use ($group_key, $key) {
            if ($key) {
                $groups[$row[$group_key]][] = $row[$key];
            } else {
                $groups[$row[$group_key]][] = $row;
            }
            return $groups;
        }, []);
    }
}
