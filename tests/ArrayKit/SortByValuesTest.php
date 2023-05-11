<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Extended\ArrayKit;

class SortByValuesTest extends TestCase
{
    /**
     * 各種配列が期待通り変換されることを確認するためのデータプロバイダ
     *
     * @return array テストデータ
     */
    public function safeProvider(): array
    {
        return [
            // Big
            [
                [
                    'hokkaido', 'aomori', 'iwate', 'akita', 'miyagi', 'yamagata', 'fukushima', 'ibaraki', 'tochigi', 'gunma', 'saitama', 'chiba', 'tokyo', 'kanagawa', 'yamanashi', 'nagano', 'niigata', 'toyama', 'ishikawa', 'fukui', 'shizuoka', 'aichi', 'gifu', 'mie', 'shiga', 'kyoto', 'oosaka', 'hyogo', 'nara', 'wakayama', 'tottori', 'shimane', 'okayama', 'hiroshima', 'yamaguchi', 'kagawa', 'ehime', 'tokushima', 'kochi', 'fukuoka', 'saga', 'nagasaki', 'kumamoto', 'ooita', 'miyazaki', 'kagoshima', 'okinawa'
                ],
                ['tokyo', 'okinawa', 'aomori'],
                [
                    'tokyo', 'okinawa', 'aomori', 'hokkaido', 'iwate', 'akita', 'miyagi', 'yamagata', 'fukushima', 'ibaraki', 'tochigi', 'gunma', 'saitama', 'chiba', 'kanagawa', 'yamanashi', 'nagano', 'niigata', 'toyama', 'ishikawa', 'fukui', 'shizuoka', 'aichi', 'gifu', 'mie', 'shiga', 'kyoto', 'oosaka', 'hyogo', 'nara', 'wakayama', 'tottori', 'shimane', 'okayama', 'hiroshima', 'yamaguchi', 'kagawa', 'ehime', 'tokushima', 'kochi', 'fukuoka', 'saga', 'nagasaki', 'kumamoto', 'ooita', 'miyazaki', 'kagoshima'
                ]
            ],
            // 要素数が同じで要素が異なる配列
            [['apple', 'banana', 'orange'], ['banana', 'orange', 'apple'], ['banana', 'orange', 'apple']],
            // 要素数が異なる配列
            [['dog', 'cat', 'hamster', 'bird'], ['cat', 'bird'], ['cat', 'bird', 'dog', 'hamster']],
            // 空の配列
            [[], [], []],
            // 重複する値がある配列
            [['x', 'a', 'b', 'b', 'a', 'y'], ['b', 'a', 'c'], ['b', 'b', 'a', 'a', 'x', 'y']],
            // 多く指定
            [[1, 2], [2, 1, 5], [2, 1]],
        ];
    }

    /**
     * ArrayKit::sort_by_values() 関数の正常系テスト
     *
     * @dataProvider safeProvider
     *
     * @param array $array1   並び替える元の配列 ['a', 'b', 'c', 'b', 'a']
     * @param array $array2   配列の値を参照する順序を示す配列 ['b', 'a', 'c']
     * @param array $expected 期待する結果の配列 ['b', 'b', 'a', 'a', 'c']
     */
    public function testSortByValues正常系(array $array1, array $array2, array $expected): void
    {
        $result = ArrayKit::sort_by_values($array1, $array2);
        $this->assertSame($expected, $result);
    }
}
