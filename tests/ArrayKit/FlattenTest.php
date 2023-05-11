<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Extended\ArrayKit;

class FlattenTest extends TestCase
{
    /**
     * フラット化関数のテスト用データを提供する
     *
     * @return array 入力と予想される結果の配列を返す
     */
    public function safeProvider(): array
    {
        return [
            [[1, [2, 3], [4]], [1, 2, 3, 4]], // 通常
            [[1, 2, [3, 4, [5, 6], 7], 8, [9]], [1, 2, 3, 4, 5, 6, 7, 8, 9]], // ややこしい
            [[1, 2, null, [], [3]], [1, 2, 3]], // 空は無視
            [
                [1, [1, [2, [3]]], 4, [5, [[[[[7]]]]]]],
                [1, 1, 2, 3, 4, 5, 7]
            ], // 複雑
        ];
    }

    /**
     * ArrayKit::flatten() 関数の正常系テスト
     * @dataProvider safeProvider
     */
    public function testFlatten正常系(array $inputArray, array $expectedResultArray): void
    {
        // フラット化関数を呼び出し、返された結果が期待通りであることを検証する。
        $result = ArrayKit::flatten($inputArray);
        $this->assertSame($expectedResultArray, $result);
    }
}
