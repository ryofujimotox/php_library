<?php

use PHPUnit\Framework\TestCase;
use FrUtility\DateTime\DateTime;

class DatetimeTest extends TestCase
{
    /**
     * 正常系テスト用データプロバイダ
     *
     * @return array テストデータ。[日付文字列, ]の連想配列。
     */
    public function safeProvider(): array
    {
        return [
            ['2030-02-21 12:31:02', '2030-02-21 00:00:00', '01', '28', '22'],
            ['2023-05-15 01:21:20', '2023-05-15 00:00:00', '01', '31', '16'],
        ];
    }

    /**
     * @dataProvider safeProvider
     */
    public function testDate正常系(string $date, $zero, $first, $last, $next): void
    {
        $Datetime = new DateTime($date);
        $result = $Datetime->getStartDateTime();
        $this->assertEquals($zero, $result);

        $result = $Datetime->getMonthFirstDate();
        $this->assertEquals($first, $result->format('d'));

        $result = $Datetime->getMonthLastDate();
        $this->assertEquals($last, $result->format('d'));

        $result = $Datetime->getNextDate();
        $this->assertEquals($next, $result->format('d'));
    }
}
