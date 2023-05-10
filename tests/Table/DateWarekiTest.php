<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Table\DateWareki;

class DateWarekiTest extends TestCase
{
    /**
     * 正常系テスト用データプロバイダ
     *
     * @return array テストデータ。[日付文字列, 和暦表記, 年数, 英字の和暦]の連想配列。
     */
    public function successWarekiProvider(): array
    {
        return [
            ['2030-01-01', '令和', 'R', 12], // 未来
            ['1989-01-08', '平成', 'H', 1], // 過去
            ['1926-12-24', '大正', 'T', 15]// 過去
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
     * DateWarekiクラスのmakeメソッドの正常系テスト
     *
     * @dataProvider successWarekiProvider
     * @param string $date 日付文字列
     * @param string $wareki 和暦表記
     */
    public function testDateWarekiFormat(string $date, string $wareki, string $en_wareki, int $year_of_era): void
    {
        $datetime = new DateTime($date);
        $DateWareki = new DateWareki($datetime);
        $currentWareki = $DateWareki->format('KX年');
        $era = $DateWareki->getEra();

        //
        $this->assertEquals("{$wareki}" . sprintf('%02d', $year_of_era) . '年', $currentWareki);
        $this->assertEquals($era['en_abbr'], $en_wareki);
    }
    /**
     * DateWarekiクラスのmakeメソッドのエラー系テスト
     *
     * @dataProvider invalidWarekiProvider
     * @param string $date 日付文字列
     */
    public function testDateWarekiFormatInvalid(string $date): void
    {
        // 例外が発生することをテスト
        $this->expectException(Exception::class);
        $datetime = new DateTime($date);
        $DateWareki = new DateWareki($datetime);
        $currentWareki = $DateWareki->format('KX年');
    }
}
