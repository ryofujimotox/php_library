<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Extended\ArrayKit;

class DifferenceTest extends TestCase
{
    /**
     * 安全なデータセットを提供する関数
     *
     * @return array テスト用の安全なデータセット
     */
    public function safeProvider(): array
    {
        // 各テストシナリオに対して、入力と予想される結果を提供します。
        return [
            // 差分なし
            [[1, 2, 3], [1, 2, 3], []], // 順番を考慮
            [[1, 2, 3], [3, 1, 2], []], // 順序不問

            // 差分あり
            [[1.5, 'a', false], [1.9, 'a', true], [1.5, false, 1.9, true]], // 様々な型
            [['a' => 2, 'c' => 6], ['a' => 1, 'x' => 6], ['a' => 1]], // 連想配列は値だけ比較する
        ];
    }

    /**
     * ArrayKit::difference() 関数の正常系テスト
     * 差分が正常に機能することをテストする
     *
     * @dataProvider safeProvider
     *
     * @param array $array1 値を比較する配列1
     * @param array $array2 値を比較する配列2
     * @param array $expected 期待される差分が含まれる配列
     *
     * @return void
     */
    public function testDifference正常系(array $array1, array $array2, array $expected): void
    {
        // 関数を呼び出し、返された結果が期待通りであることを検証します。
        $result = ArrayKit::difference($array1, $array2);
        $this->assertSame($expected, $result);
    }
}
