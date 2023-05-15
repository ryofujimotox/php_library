<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Extended\DictionaryKit;

class FilterExcludedKeysTest extends TestCase
{
    /**
     * 安全なプロバイダを返す
     *
     * @return array
     */
    public function safeProvider(): array
    {
        return [
            [['delete'], ['delete' => 51, 'live' => 21], ['live' => 21]], // 普通
            [['delete', 'nondelete'], ['delete' => 51, 'live' => 21], ['live' => 21]], // 多い
            [['delete'], [], []], // 少ない
            [[], [], []], // 空白
        ];
    }

    /**
     * テスト用のデータプロバイダ。
     * filterExcludedKeysメソッドに正しい引数を渡しているかを確認するテストを行う。
     *
     * @dataProvider safeProvider
     */
    public function testFilterExcludedKeys(array $excludes, array $dictionary, array $expectedResult): void
    {
        // 辞書配列から除外するキーをフィルタリングする。
        $actualResult = DictionaryKit::filterExcludedKeys($excludes, $dictionary);

        // 期待される結果と一致することを確認
        $this->assertSame($expectedResult, $actualResult);
    }
}
