<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Extended\ArrayKit;

class AreSameTest extends TestCase
{
    /**
     * 各種配列が期待通り変換されることを確認するためのデータプロバイダ
     *
     * @return array テストデータ
     */
    public function safeProvider(): array
    {
        return [
            [[1, 2, 3], [1, 2, 3], true], // 通常
            [[1.5, 'a', false], [1.5, 'a', false], true], // 複雑な型
            [['a' => 1, 'b' => 2], ['a' => 1, 'b' => 2], true], // 連想配列もある程度は可能
            // false
            [[1, 2, 3], [3, 1, 2], false], // ソート前後で配列が異なる場合のテストケース
        ];
    }

    /**
     * ArrayKit::are_same() 関数の正常系テスト
     *
     * @dataProvider safeProvider
     *
     * @param array $array1 値を比較する配列1
     * @param array $array2 値を比較する配列2
     * @param bool $expected 期待値
     */
    public function testAreSame正常系(array $array1, array $array2, bool $expected): void
    {
        $result = ArrayKit::are_same($array1, $array2);
        $this->assertSame($expected, $result);
    }
}
