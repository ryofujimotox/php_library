<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Extended\DictionaryKit;

class FilterByKeysTest extends TestCase
{
    /**
     * 安全なプロバイダを返す
     *
     * @return array
     */
    public function safeProvider(): array
    {
        return [
            // 既存の値と同じタイプの値を追加        [[], [], []],
            [[0, 1], ['a', 'b', 'c'], [0 => 'a', 1 => 'b']],
            [['test2'], ['test1' => 1, 'test2' => 2, 'test3' => 3], ['test2' => 2]],

            // 整数値以外の値を含む配列を追加
            [['bar'], ['foo' => 1, 'bar' => 'two', 'baz' => 3], ['bar' => 'two']],
            [[1, 3], ['a', 'b', 'c', 'd', 'e'], [1 => 'b', 3 => 'd']],

            // 連想配列を追加
            [['name', 'age'], ['name' => 'John', 'age' => 25, 'gender' => 'Male'], ['name' => 'John', 'age' => 25]],
            [['title', 'author'], ['title' => 'The Catcher in the Rye', 'author' => 'J.D. Salinger', 'year' => 1951], ['title' => 'The Catcher in the Rye', 'author' => 'J.D. Salinger']]
        ];
    }

    /**
     * テスト用のデータプロバイダ。
     * filterExcludedKeysメソッドに正しい引数を渡しているかを確認するテストを行う。
     *
     * @dataProvider safeProvider
     */
    public function testFilterByKeys(array $indexes, array $array, array $result): void
    {
        // 辞書配列から除外するキーをフィルタリングする。
        $actualResult = DictionaryKit::filterByKeys($indexes, $array);

        // 期待される結果と一致することを確認
        $this->assertSame($result, $actualResult);
    }
}
