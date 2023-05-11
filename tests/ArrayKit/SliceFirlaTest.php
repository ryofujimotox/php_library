<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Extended\ArrayKit;

class SliceFirlaTest extends TestCase
{
    /**
     * 安全にslice_firla関数が動作するためのデータプロバイダ
     * 配列の先頭と末尾から指定された数の要素を切り取る。
     *
     * @return array データセット
     * @see ArrayKit::slice_firla()
     */
    public function safeProvider(): array
    {
        return [
            // // [切り取る元の配列, 先頭から残す要素数, 末尾から残す要素数, 期待する配列]
            [[], 1, 1, []], // 空の場合のテスト
            [[1, 2, 3], 1, 1, [1, 3]], // スライス対象が連番の場合のテスト
            [[1, 2], 5, 5, [1, 2, 1, 2]],
            [[1, 2, 3], 5, 5, [1, 2, 3, 1, 2, 3]],
            [[20, 8, 2, 9], 1, 2, [20, 2, 9]], // スライス対象が連番でない場合（要素の大きさと順番は関係ないこと）のテスト
            [[20, 8, 2, 9], 3, 3, [20, 8, 2, 8, 2, 9]], // スライス対象の範囲を超えてスライスする場合のテスト
        ];
    }

    /**
     * ArrayKit::slice_firla() 関数の正常系テスト
     *
     * @param array $array 切り取る元の配列
     * @param int $first 先頭から残す要素数
     * @param int $last 末尾から残す要素数
     * @param array $want 期待する配列
     *
     * @dataProvider safeProvider
     */
    public function testSliceFirla正常系(array $array, int $first, int $last, array $want): void
    {
        $sliced_array = ArrayKit::slice_firla($array, $first, $last);
        $this->assertSame($want, $sliced_array);
    }
}
