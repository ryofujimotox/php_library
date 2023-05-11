<?php
use PHPUnit\Framework\TestCase;
use FrUtility\DateTime\DateTimeFromTimestamp;

class DateTimeFromTimestampTest extends TestCase
{
    /**
     * setSafeTimestamp() 関数の正常系テスト
     * @dataProvider TestProviders\TimestampProvider::safeProvider
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
     * @dataProvider TestProviders\TimestampProvider::typeErrorProvider
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
     * @dataProvider TestProviders\TimestampProvider::exceptionErrorProvider
     */
    public function testSetSafeTimestampエラー系Exception($timestamp, $timezone)
    {
        // タイムゾーンを指定して setSafeTimestamp() 関数を呼び出す
        $this->expectException(Exception::class);
        $dateTime = new DateTimeFromTimestamp($timestamp, $timezone);
    }
}
