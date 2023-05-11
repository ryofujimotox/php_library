<?php
use PHPUnit\Framework\TestCase;
use FrUtility\DateTime\DateTimeFromTimestamp;

class DateTimeFromTimestampTest extends TestCase
{
    /**
     * 正常系テスト用データプロバイダ
     * タイムゾーン毎に異なる日時のフォーマットをテストする
     */
    public function safeProvider(): array
    {
        return [
            [1683766360, '2023-05-11 01:52:40', 'Europe/London'],
            [1683766360, '2023-05-11 09:52:40', 'Asia/Tokyo'],
            [1683766360, '2023-05-10 20:52:40', 'America/New_York']
        ];
    }

    /**
     * 無効な引数を用いた setSafeTimestamp() のパラメータプロバイダ
     * Exception を返す。
     */
    public function exceptionErrorProvider()
    {
        return [
            ['1609459200', 123],          // タイムゾーンに数値を指定（string 以外）
        ];
    }

    /**
     * 無効な引数を用いた setSafeTimestamp() のパラメータプロバイダ
     * TypeError を返す。
     */
    public function typeErrorProvider()
    {
        return [
            ['invalid'],   // タイムスタンプに文字列を指定（int 以外）
            [null, null]                // タイムスタンプとタイムゾーンに null を指定
        ];
    }

    /**
     * setSafeTimestamp() 関数の正常系テスト
     * @dataProvider safeProvider
     */
    public function testSetSafeTimestamp正常系(int $timestamp, string $expectedDateTime, string $timezone): void
    {
        // タイムゾーンを指定して setSafeTimestamp() 関数を呼び出す
        $dateTime = new DateTimeFromTimestamp($timestamp, $timezone);

        // 期待値と実際の値を比較する
        $this->assertEquals($expectedDateTime, $dateTime->format('Y-m-d H:i:s'));
    }

    /**
     * setSafeTimestamp() 関数のエラー系テスト
     *
     * @dataProvider typeErrorProvider
     */
    public function testSetSafeTimestampエラー系Type($timestamp)
    {
        // タイムゾーンを指定して setSafeTimestamp() 関数を呼び出す
        $this->expectException(TypeError::class);
        $dateTime = new DateTimeFromTimestamp($timestamp);
    }

    /**
     * setSafeTimestamp() 関数のエラー系テスト
     *
     * @dataProvider exceptionErrorProvider
     */
    public function testSetSafeTimestampエラー系Exception($timestamp, $timezone)
    {
        // タイムゾーンを指定して setSafeTimestamp() 関数を呼び出す
        $this->expectException(Exception::class);
        $dateTime = new DateTimeFromTimestamp($timestamp, $timezone);
    }
}
