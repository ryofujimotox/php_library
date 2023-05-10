<?php

use FrUtility\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

class DateTimeTimestampTest extends TestCase
{
    /**
     * setSafeTimestamp() 関数の正常系テスト
     */
    public function testCreateFromTimestamp()
    {
        // テスト用タイムスタンプを用意する
        $timestamp = 1609459200; // 2021-01-01 00:00:00

        // タイムゾーンを指定して setSafeTimestamp() 関数を呼び出す
        $dateTime = new DateTime();
        $dateTime = $dateTime->setSafeTimestamp($timestamp, 'America/New_York');

        // 期待値と実際の値を比較する
        $this->assertEquals('2020-12-31 19:00:00', $dateTime->format('Y-m-d H:i:s'));
    }

    /**
     * setSafeTimestamp() 関数のエラー系テスト
     *
     * @dataProvider invalidTypeProvider
     */
    public function testCreateFromTimestampInvalidType($timestamp)
    {
        // タイムゾーンを指定して setSafeTimestamp() 関数を呼び出す
        $this->expectException(TypeError::class);
        $dateTime = new DateTime();
        $dateTime = $dateTime->setSafeTimestamp($timestamp, 'Asia/Tokyo');
    }

    /**
     * setSafeTimestamp() 関数のエラー系テスト
     *
     * @dataProvider invalidArgumentProvider
     */
    public function testCreateFromTimestampInvalidArgument($timestamp, $timezone)
    {
        // タイムゾーンを指定して setSafeTimestamp() 関数を呼び出す
        $this->expectException(Exception::class);
        $dateTime = new DateTime();
        $dateTime = $dateTime->setSafeTimestamp($timestamp, $timezone);
    }

    /**
     * 無効な引数を用いた setSafeTimestamp() のパラメータプロバイダ
     */
    public function invalidTypeProvider()
    {
        return [
            ['invalid'],   // タイムスタンプに文字列を指定（int 以外）
            [null, null]                // タイムスタンプとタイムゾーンに null を指定
        ];
    }

    /**
     * 無効な引数を用いた setSafeTimestamp() のパラメータプロバイダ
     */
    public function invalidArgumentProvider()
    {
        return [
            ['1609459200', 123],          // タイムゾーンに数値を指定（string 以外）
        ];
    }
}
