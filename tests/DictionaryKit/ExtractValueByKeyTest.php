<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Extended\DictionaryKit;

class ExtractValueByKeyTest extends TestCase
{
    /**
     * 安全なプロバイダを返す
     *
     * @return array 安全なプロバイダ
     */
    public function getSafeProvider(): array
    {
        // テストするデータを定義する。
        return [
            [
                ['test' => [1], 'test2' => ['test' => [2, 3], 'test3' => [4]]],
                'test',
                [1, 2, 3],
            ],
            [
                ['key1' => ['subkey1' => ['value1', 'value2'], 'subkey2' => ['value3']]],
                'subkey1',
                ['value1', 'value2'],
            ],
            [
                ['key1' => ['subkey1' => ['value1', 'value2'], 'subkey2' => ['value3']]],
                'subkey2',
                ['value3'],
            ],
            [
                ['key1' => ['subkey1' => ['value1', 'value2'], 'subkey2' => ['value3']]],
                'nonexistent',
                [],
            ],
        ];
    }

    /**
     * 安全な値の抽出ができるかテストする
     *
     * @dataProvider getSafeProvider
     *
     * @param array $array 対象となる配列
     * @param string $key 抽出する値のキー
     * @param array $expectedResult 期待される結果
     */
    public function testExtractValueByKey(array $array, string $key, array $expectedResult): void
    {
        // 辞書配列から除外するキーをフィルタリングする
        $actualResult = DictionaryKit::extractValueByKey($array, $key);

        // 期待される結果と一致することを確認
        $this->assertSame($expectedResult, $actualResult);
    }
}
