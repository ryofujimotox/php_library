<?php

use FrUtility\Extended\DictionaryKit;
use PHPUnit\Framework\TestCase;
use FrUtility\Other\Random;

class RandomTest extends TestCase
{
    /**
     * ランダムな文字列が生成されることをテストする
     */
    public function testString正常系(): void
    {
        // ランダムな文字列を生成する。
        $result = Random::string(100);

        // 生成された文字列が期待通りであることを検証する。
        $this->assertMatchesRegularExpression('/[A-Z]/', $result);
        $this->assertMatchesRegularExpression('/[a-z]/', $result);
        $this->assertMatchesRegularExpression('/[0-9]/', $result);
    }

    /**
     * ランダムな整数が生成されることをテストする
     */
    public function testInteger正常系(): void
    {
        // ランダムな整数を生成する。
        $result = Random::int(100);

        // 生成された値が期待通りであることを検証する。
        $this->assertMatchesRegularExpression('/^[0-9]*$/', $result);
    }

    /**
     * ランダムな配列キーが生成されることをテストする
     */
    public function testArrayKey正常系(): void
    {
        // テスト用の配列を定義する。
        $array = ['test1' => 1, 'test2' => 2, 'test3' => 3];

        // 配列キーを生成する。
        $result = [];
        foreach (range(1, 100) as $cnt) {
            $result[] = Random::arrayKey($array);
        }

        // 期待される配列キーがすべて含まれていることを確認する。
        $diff = DictionaryKit::filterExcludedKeys($result, $array);
        $this->assertEmpty($diff);
    }
}
