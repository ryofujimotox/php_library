<?php
namespace TestProviders;

class TimestampProvider
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
}
