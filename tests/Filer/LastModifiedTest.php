<?php
use PHPUnit\Framework\TestCase;
use FrUtility\Other\Filer;

class LastModifiedTest extends TestCase
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
     * @dataProvider safeProvider
     */
    public function testGetLastModified正常系(int $timestamp, string $expectedDatetime, string $timezone): void
    {
        // テスト用のファイルを作成し、最終更新日時を指定されたタイムスタンプに設定する
        $testFilePath = Filer::createSampleFile('test.txt', '', $timestamp);

        // テスト対象の関数を呼び出し、結果を確認する
        $modified = Filer::getLastModified($testFilePath, $timezone);
        $this->assertEquals($modified->format('Y-m-d H:i:s'), $expectedDatetime);

        // テスト用のファイルを削除する
        unlink($testFilePath);
    }

    /**
     * ファイルが存在しない場合、例外が発生することを確認するテスト
     */
    public function testGetLastModifiedエラー系FileNotFound(): void
    {
        // 存在しないファイルパスを指定して、例外が発生することを確認する
        $this->expectException(\Exception::class);
        Filer::getLastModified('/path/to/invalid/file.txt');
    }
}
