<?php

use PHPUnit\Framework\TestCase;
use FrUtility\DateTime\DateTime;

class FormatTest extends TestCase
{
    /**
     * 正常系テスト用データプロバイダ
     *
     * @return array テストデータ。[日付文字列, 和暦表記, 年数, 英字の和暦]の連想配列。
     */
    public function successWarekiProvider(): array
    {
        return [
            ['2030-01-01', '令和12年', 'KX年'], // 未来
            ['1989-01-08', '平成01年', 'KX年'], // 過去
            ['1926-12-24', '大正15年金曜日', 'KX年W曜日'], // 過去
        ];
    }

    /**
     * エラー系テスト用データプロバイダ
     *
     *  @return array テストデータ。[日付文字列]の連想配列。
     */
    public function invalidWarekiProvider(): array
    {
        return [
            ['1868-01-24'] // 元号開始前の日付
        ];
    }

    /**
     * formatの正常系テスト
     *
     * @dataProvider successWarekiProvider
     * @param string $date 日付文字列
     * @param string $result 期待する値
     * @param string $format フォーマット
     */
    public function testFormat正常系(string $date, string $result, string $format): void
    {
        $Wareki = new DateTime($date);
        $current = $Wareki->format($format);

        //
        $this->assertEquals($result, $current);
    }

    /**
     * Warekiクラスのmakeメソッドのエラー系テスト
     *
     * @dataProvider invalidWarekiProvider
     * @param string $date 日付文字列
     */
    public function testWarekiFormatInvalid(string $date): void
    {
        // 例外が発生することをテスト
        $Wareki = new DateTime($date);
        $currentWareki = $Wareki->format('KX年');
        $this->assertEquals('00年', $currentWareki);
    }
}
